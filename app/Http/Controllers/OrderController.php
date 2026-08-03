<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function customerOrders()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return redirect('/');
        }

        $orders = DB::table('orders')
            ->where('customer_id', $customerProfile->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewedItems = [];
        if (SchemaHasTable('product_reviews')) {
            $reviewedItems = DB::table('product_reviews')
                ->where('user_id', $userId)
                ->select('order_id', 'product_id')
                ->get()
                ->map(function ($review) {
                    return $review->order_id . '_' . $review->product_id;
                })->toArray();
        }

        foreach ($orders as $order) {
            $order->items = DB::table('order_items')
                ->leftJoin('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                ->leftJoin('products', function($join) {
                    $join->on('product_sizes.product_id', '=', 'products.id')
                         ->orWhereRaw('order_items.product_name = products.name');
                })
                ->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
                ->where('order_items.order_id', $order->id)
                ->select('order_items.*', 'products.name as product_name', 'products.image as product_image', 'sizes.name as size_name', 'products.id as product_id')
                ->get();

            foreach ($order->items as $item) {
                $item->is_reviewed = in_array($order->id . '_' . $item->product_id, $reviewedItems);
                $item->toppings = DB::table('order_item_toppings')
                    ->join('toppings', 'order_item_toppings.topping_id', '=', 'toppings.id')
                    ->where('order_item_toppings.order_item_id', $item->id)
                    ->select('order_item_toppings.*', 'toppings.name as topping_name')
                    ->get();
            }

            $order->address = DB::table('customer_addresses')
                ->where('id', $order->address_id ?? 0)
                ->orWhere('address', $order->delivery_address ?? '')
                ->first();
                
            $order->shipper = null;
            if (isset($order->shipper_id) && $order->shipper_id) {
                $order->shipper = DB::table('shipper_profiles')
                    ->join('users', 'shipper_profiles.user_id', '=', 'users.id')
                    ->where('shipper_profiles.id', $order->shipper_id)
                    ->select('users.name as name')
                    ->first();
            }
        }

        $activeOrders = $orders->filter(function ($order) {
            $status = strtolower($order->status ?? $order->order_status ?? '');
            return !in_array($status, ['completed', 'cancelled']);
        });

        $historyOrders = $orders->filter(function ($order) {
            $status = strtolower($order->status ?? $order->order_status ?? '');
            return in_array($status, ['completed', 'cancelled']);
        });

        return view('customer.orders', compact('activeOrders', 'historyOrders', 'orders'));
    }

    public function placeOrder(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $request->validate([
            'receiver_name'   => ['required', 'string', 'max:255'],
            'receiver_phone'  => ['required', 'string', 'max:20'],
            'address'         => ['required', 'string'],
            'payment_method'  => ['required', 'in:cash,momo,vnpay,bank,vietqr'],
            'note'            => ['nullable', 'string'],
        ]);

        $profile = CustomerProfile::where('user_id', $userId)->first();
        if (!$profile) {
            return back()->with('error', 'Không tìm thấy hồ sơ khách hàng.');
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
            return redirect()->route('cart.index')->with('error', 'Không có sản phẩm nào được chọn để thanh toán.');
        }

        $createdOrderCode = null;

        DB::transaction(function () use ($request, $profile, $cartItems, $userId, $checkoutItemIds, &$createdOrderCode) {
            // Save address
            CustomerAddress::firstOrCreate([
                'customer_id'    => $profile->id,
                'address'        => $request->address,
            ], [
                'is_default'     => false,
            ]);

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }
            $shippingFee = $subtotal > 0 ? 15000 : 0;
            $tax = round($subtotal * 0.08);
            $discount    = 0;
            $total       = $subtotal + $shippingFee + $tax - $discount;

            $orderCode = 'ORD-' . strtoupper(uniqid());
            $createdOrderCode = $orderCode;

            $order = Order::create([
                'customer_id'     => $profile->id,
                'code'            => $orderCode,
                'order_source'    => 'WEBSITE',
                'order_type'      => 'DELIVERY',
                'order_status'    => 'PENDING',
                'status'          => 'pending',
                'receiver_name'   => $request->receiver_name,
                'receiver_phone'  => $request->receiver_phone,
                'delivery_address'=> $request->address,
                'customer_note'   => $request->note,
                'subtotal'        => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee'    => $shippingFee,
                'tax_amount'      => $tax,
                'total_amount'    => $total,
                'created_by'      => $userId
            ]);

            foreach ($cartItems as $item) {
                $productName = '';
                $sizeName = '';
                if ($item['product_id']) {
                    $p = Product::find($item['product_id']);
                    if ($p) $productName = $p->name;
                }
                if ($item['product_size_id']) {
                    $ps = ProductSize::with('size')->find($item['product_size_id']);
                    if ($ps && $ps->size) $sizeName = $ps->size->name;
                }

                $orderItem = OrderItem::create([
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

            Payment::create([
                'order_id'       => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'PENDING',
                'amount'         => $total,
            ]);

            $order->deductInventory();

            $profile->increment('total_orders');
            $profile->increment('total_spent', $total);

            // Clear only purchased items from cart
            $currentCart = session('cart', []);
            foreach ($checkoutItemIds as $id) {
                unset($currentCart[$id]);
            }
            session(['cart' => $currentCart]);
            session()->forget('checkout_items');
        });

        if (in_array($request->payment_method, ['vietqr', 'bank', 'momo'])) {
            return redirect()->route('customer.orders')->with([
                'success' => 'Đặt hàng thành công! Vui lòng quét mã VietQR để hoàn tất thanh toán.',
                'show_vietqr' => $createdOrderCode
            ]);
        }

        return redirect()->route('customer.orders')->with('success', 'Đặt hàng thành công!');
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) return back()->with('error', 'Không tìm thấy thông tin khách hàng');

        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerProfile->id)
            ->first();

        if (!$order) {
            return back()->with('error', 'Đơn hàng không tồn tại hoặc bạn không có quyền hủy.');
        }

        $status = strtolower($order->status ?? $order->order_status ?? '');
        if (in_array($status, ['preparing', 'shipping', 'delivering', 'completed', 'cancelled'])) {
            return back()->with('error', 'Đơn hàng đang chuẩn bị hoặc đã giao, không thể hủy.');
        }

        $cancelReason = request('cancel_reason') ?: 'Không có lý do';

        $order->status = 'cancelled';
        $order->order_status = 'CANCELLED';
        $order->cancel_reason = $cancelReason;
        $order->cancelled_at = now();
        $order->save();
        
        $order->restoreInventory();

        try {
            $user = DB::table('users')->where('id', $userId)->first();
            if ($user && $user->email) {
                Mail::to($user->email)
                    ->send(new \App\Mail\OrderStatusChanged($order, $user->username, 'Đã bị hủy bởi khách hàng'));
            }
        } catch (\Exception $e) {
            Log::error('Mail Error: ' . $e->getMessage());
        }

        return back()->with('cancel_success', 'Đã hủy đơn hàng! Cảm ơn bạn đã góp ý kiến.');
    }
}

function SchemaHasTable($table) {
    return \Illuminate\Support\Facades\Schema::hasTable($table);
}
