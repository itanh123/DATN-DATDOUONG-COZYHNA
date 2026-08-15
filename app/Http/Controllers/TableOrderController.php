<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TableOrderController extends Controller
{
    public function loginWithQr($token)
    {
        $table = \App\Models\RestaurantTable::where('qr_token', $token)->where('status', '!=', 'disabled')->first();

        if (!$table) {
            return redirect('/')->with('error', 'Mã QR không hợp lệ hoặc bàn đã bị vô hiệu hóa.');
        }

        // Cập nhật trạng thái bàn thành "Có khách" nếu đang trống
        if ($table->status === 'available') {
            $table->status = 'occupied';
            $table->save();
        }

        // Tự động tạo "tài khoản bàn" nếu chưa có
        $username = 'table_' . $table->id;
        $user = \App\Models\User::where('username', $username)->first();
        
        if (!$user) {
            $roleId = DB::table('roles')->where('code', 'customer')->value('id');
            $user = \App\Models\User::create([
                'username' => $username,
                'email' => $username . '@local.com',
                'phone' => '0' . rand(100000000, 999999999),
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(10)),
                'role_id' => $roleId,
                'status' => true,
            ]);
            
            DB::table('customer_profiles')->insert([
                'user_id' => $user->id,
                'full_name' => $table->table_name,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Logout current user if any
        if (session('user_id')) {
            session()->forget('user_id');
            session()->forget('role_code');
            session()->forget('username');
            session()->forget('avatar');
        }

        // Login as table user via custom session
        session([
            'user_id' => $user->id,
            'role_code' => 'customer',
            'username' => $user->username,
            'is_table_order' => true,
            'table_id' => $table->id,
            'table_name' => $table->table_name,
            'table_login_time' => now()->timestamp,
        ]);

        return redirect('/')->with('success', 'Đã kết nối với ' . $table->table_name . '. Vui lòng chọn món.');
    }

    public function confirmOrder(Request $request)
    {
        $userId = session('user_id');
        $isTableOrder = session('is_table_order');
        $tableId = session('table_id');

        if (!$userId || !$isTableOrder || !$tableId) {
            return redirect('/login')->with('error', 'Vui lòng quét lại mã QR tại bàn.');
        }

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return redirect('/')->with('error', 'Lỗi dữ liệu tài khoản bàn.');
        }

        $cartItemsRaw = session('cart', []);
        $checkoutItemIds = session('checkout_items', []);
        $cartItems = [];
        
        foreach ($cartItemsRaw as $item) {
            if (in_array($item['id'], $checkoutItemIds)) {
                $cartItems[] = $item;
            }
        }

        if (empty($cartItems)) {
            return redirect('/customer/cart')->with('error', 'Không có sản phẩm nào được chọn để thanh toán.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $orderCode = 'TBL-' . strtoupper(uniqid());

            $appliedVoucher = session('applied_voucher');
            $discountAmount = 0;
            $voucherId = null;
            if ($appliedVoucher) {
                $discountAmount = $appliedVoucher['discount_amount'];
                $voucherId = $appliedVoucher['id'];
                
                // Cập nhật số lượng voucher
                $voucher = \App\Models\Voucher::find($voucherId);
                if ($voucher && $voucher->quantity > 0) {
                    $voucher->increment('used');
                }
            }

            // Create Order using Eloquent for consistency
            $order = \App\Models\Order::create([
                'customer_id'     => $customerProfile->id,
                'order_code'      => $orderCode,
                'order_source'    => 'WEBSITE',
                'order_type'      => 'AT_TABLE',
                'order_status'    => 'PENDING',
                'status'          => 'pending',
                'receiver_name'   => $customerProfile->full_name,
                'receiver_phone'  => 'N/A',
                'delivery_address'=> 'Tại bàn',
                'subtotal'        => $subtotal,
                'discount_amount' => $discountAmount,
                'voucher_id'      => $voucherId,
                'shipping_fee'    => 0,
                'tax_amount'      => 0,
                'total_amount'    => max(0, $subtotal - $discountAmount),
                'created_by'      => $userId
            ]);

            // Add Order Items
            foreach ($cartItems as $item) {
                $productName = '';
                $sizeName = '';
                if ($item['product_id']) {
                    $p = \App\Models\Product::find($item['product_id']);
                    if ($p) $productName = $p->name;
                }
                if ($item['product_size_id']) {
                    $ps = \App\Models\ProductSize::with('size')->find($item['product_size_id']);
                    if ($ps && $ps->size) $sizeName = $ps->size->name;
                }

                $orderItem = \App\Models\OrderItem::create([
                    'order_id'        => $order->id,
                    'product_size_id' => $item['product_size_id'],
                    'product_name'    => $productName,
                    'size_name'       => $sizeName,
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $item['unit_price'],
                    'discount'        => 0,
                    'final_price'     => $item['unit_price'],
                ]);

                if (!empty($item['toppings'])) {
                    foreach ($item['toppings'] as $topping) {
                        \App\Models\OrderItemTopping::create([
                            'order_item_id' => $orderItem->id,
                            'topping_id'    => $topping['id'],
                            'quantity'      => $item['quantity'],
                            'unit_price'    => $topping['price'],
                            'total_price'   => $topping['price'] * $item['quantity'],
                        ]);
                    }
                }
            }

            \App\Models\Payment::create([
                'order_id'       => $order->id,
                'payment_method' => 'cash',
                'payment_status' => 'PENDING',
                'amount'         => $subtotal + round($subtotal * 0.08),
            ]);

            // Clear checkout items from cart
            $currentCart = session('cart', []);
            $checkoutItemIds = session('checkout_items', []);
            foreach ($checkoutItemIds as $id) {
                unset($currentCart[$id]);
            }
            session(['cart' => $currentCart]);
            session()->forget('checkout_items');
            session()->forget('applied_voucher');

            DB::commit();

            return redirect('/table/order/success')->with('success', 'Đã đặt món thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/customer/cart')->with('error', 'Có lỗi xảy ra khi đặt món: ' . $e->getMessage());
        }
    }

    public function success()
    {
        if (!session('is_table_order')) {
            return redirect('/');
        }
        return view('customer.table_order_success');
    }

    public function callStaff()
    {
        if (!session('is_table_order') || !session('table_id')) {
            return response()->json(['success' => false, 'message' => 'Không có quyền truy cập.'], 403);
        }

        // Avoid multiple pending calls from the same table to prevent spam
        $existing = \Illuminate\Support\Facades\DB::table('table_calls')
            ->where('table_id', session('table_id'))
            ->where('status', 'pending')
            ->first();

        if (!$existing) {
            \Illuminate\Support\Facades\DB::table('table_calls')->insert([
                'table_id' => session('table_id'),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }
}
