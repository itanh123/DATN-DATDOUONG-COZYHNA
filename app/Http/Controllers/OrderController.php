<?php

namespace App\Http\Controllers;

use App\Models\Profiles\CustomerAddress;
use App\Models\Profiles\CustomerProfile;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Orders\Payment;
use App\Models\Products\Product;
use App\Models\Products\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function place(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $request->validate([
            'receiver_name'   => ['required', 'string', 'max:255'],
            'receiver_phone'  => ['required', 'string', 'max:20'],
            'address'         => ['required', 'string'],
            'payment_method'  => ['required', 'in:cash,momo,vnpay,bank'],
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

        DB::transaction(function () use ($request, $profile, $cartItems, $userId, $checkoutItemIds) {
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

            $order = Order::create([
                'customer_id'     => $profile->id,
                'code'            => 'ORD-' . strtoupper(uniqid()),
                'order_source'    => 'WEBSITE',
                'order_type'      => 'DELIVERY',
                'order_status'    => 'PENDING',
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
                        $orderItem->toppings()->attach($topping['id'], [
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

        return redirect('/customer/orders')->with('success', 'Đặt hàng thành công! Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất.');
    }

    public function history()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $profile = CustomerProfile::where('user_id', $userId)->first();
        $orders  = [];

        if ($profile) {
            $orders = Order::where('customer_id', $profile->id)
                ->with(['items.productSize.product', 'items.productSize.size'])
                ->orderByDesc('created_at')
                ->get();
        }

        return view('customer.orders', compact('orders'));
    }

    public function cancel($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $profile = CustomerProfile::where('user_id', $userId)->first();
        if (!$profile) {
            return response()->json(['error' => 'Không tìm thấy hồ sơ.'], 404);
        }

        $order = Order::where('id', $id)
            ->where('customer_id', $profile->id)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Không tìm thấy đơn hàng.'], 404);
        }

        if (!in_array($order->order_status, ['PENDING', 'CONFIRMED'])) {
            return response()->json(['error' => 'Không thể hủy đơn hàng đang trong trạng thái này.'], 400);
        }

        $order->order_status = 'CANCELLED';
        $order->cancelled_at = now();
        $order->save();

        return response()->json(['success' => true, 'message' => 'Đã hủy đơn hàng thành công.']);
    }
}
