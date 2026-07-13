<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomerAddress;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\OrderItem;
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

        // Get customer profile
        $profile = CustomerProfile::where('user_id', $userId)->first();
        if (!$profile) {
            return back()->with('error', 'Không tìm thấy hồ sơ khách hàng.');
        }

        // Get cart
        $cart = Cart::where('customer_id', $profile->id)->first();
        if (!$cart) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $cartItems = CartItem::where('cart_id', $cart->id)
            ->with(['productSize'])
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        DB::transaction(function () use ($request, $profile, $cart, $cartItems) {
            // Create or find address
            $address = CustomerAddress::create([
                'customer_id'    => $profile->id,
                'receiver_name'  => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'address'        => $request->address,
                'is_default'     => false,
            ]);

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                if ($item->productSize) {
                    $subtotal += $item->productSize->selling_price * $item->quantity;
                }
            }
            $shippingFee = 15000;
            $discount    = 0;
            $total       = $subtotal + $shippingFee - $discount;

            // Create Order
            $order = Order::create([
                'order_code'      => 'ORD-' . strtoupper(uniqid()),
                'customer_id'     => $profile->id,
                'address_id'      => $address->id,
                'subtotal'        => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee'    => $shippingFee,
                'total_amount'    => $total,
                'payment_method'  => $request->payment_method,
                'status'          => 'pending',
                'note'            => $request->note,
                'ordered_at'      => now(),
            ]);

            // Create Order Items
            foreach ($cartItems as $item) {
                if (!$item->productSize) continue;
                $unitPrice = $item->productSize->selling_price;
                OrderItem::create([
                    'order_id'        => $order->id,
                    'product_size_id' => $item->product_size_id,
                    'quantity'        => $item->quantity,
                    'unit_price'      => $unitPrice,
                    'total_price'     => $unitPrice * $item->quantity,
                    'note'            => null,
                ]);
            }

            // Update customer stats
            $profile->increment('total_orders');
            $profile->increment('total_spent', $total);

            // Clear cart
            $cart->items()->delete();
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
                ->orderByDesc('ordered_at')
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

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json(['error' => 'Không thể hủy đơn hàng đang trong trạng thái này.'], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Đã hủy đơn hàng thành công.']);
    }
}
