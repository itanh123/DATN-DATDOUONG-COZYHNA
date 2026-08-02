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
        $table = DiningTable::where('qr_token', $token)->where('status', true)->first();

        if (!$table) {
            return redirect('/')->with('error', 'Mã QR không hợp lệ hoặc bàn đã bị vô hiệu hóa.');
        }

        $user = $table->user;
        if (!$user) {
            return redirect('/')->with('error', 'Lỗi dữ liệu bàn.');
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
            'table_name' => $table->name,
            'table_login_time' => now()->timestamp,
        ]);

        return redirect('/')->with('success', 'Đã kết nối với ' . $table->name . '. Vui lòng chọn món.');
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

        $cart = DB::table('carts')->where('customer_id', $customerProfile->id)->first();
        if (!$cart) {
            return redirect('/customer/checkout')->with('error', 'Không tìm thấy giỏ hàng trong CSDL.');
        }

        $cartItems = DB::table('cart_items')->where('cart_id', $cart->id)->get();
        if ($cartItems->isEmpty()) {
            return redirect('/customer/checkout')->with('error', 'Giỏ hàng không có sản phẩm nào.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $productSize = DB::table('product_sizes')->where('id', $item->product_size_id)->first();
            if ($productSize) {
                $subtotal += $productSize->selling_price * $item->quantity;
            }
        }

        DB::beginTransaction();
        try {
            // Create Order
            $orderId = DB::table('orders')->insertGetId([
                'order_code' => 'TBL' . date('YmdHis') . rand(100, 999),
                'customer_id' => $customerProfile->id,
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_fee' => 0,
                'total_amount' => $subtotal,
                'payment_method' => 'cash', // Default to cash for table order, can pay later
                'status' => 'pending',
                'order_type' => 'at_table', // Custom column we added
                'ordered_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add Order Items
            foreach ($cartItems as $item) {
                $productSize = DB::table('product_sizes')->where('id', $item->product_size_id)->first();
                if ($productSize) {
                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_size_id' => $item->product_size_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $productSize->selling_price,
                        'total_price' => $productSize->selling_price * $item->quantity,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Clear Cart
            DB::table('cart_items')->where('cart_id', $cart->id)->delete();
            DB::table('carts')->where('id', $cart->id)->delete(); // Or just leave cart items deleted

            DB::commit();

            return redirect('/table/order/success')->with('success', 'Đã đặt món thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/customer/checkout')->with('error', 'Có lỗi xảy ra khi đặt món: ' . $e->getMessage());
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
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
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
