<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán.');
        }

        $user = \App\Models\User::find($userId);

        $customerProfile = DB::table('customer_profiles')->where('user_id', $user->id)->first();
        
        $addresses = [];
        $cartItems = [];
        $cartTotal = 0;

        if ($customerProfile) {
            $addresses = DB::table('customer_addresses')
                ->where('customer_id', $customerProfile->id)
                ->orderBy('is_default', 'desc')
                ->get();

            $cart = DB::table('carts')
                ->where('customer_id', $customerProfile->id)
                ->first();

            if ($cart) {
                $cartItems = DB::table('cart_items')
                    ->join('product_sizes', 'cart_items.product_size_id', '=', 'product_sizes.id')
                    ->join('products', 'product_sizes.product_id', '=', 'products.id')
                    ->join('sizes', 'product_sizes.size_id', '=', 'sizes.id')
                    ->where('cart_items.cart_id', $cart->id)
                    ->select(
                        'cart_items.id as cart_item_id',
                        'cart_items.quantity',
                        'products.name as product_name',
                        'products.image as product_image',
                        'sizes.name as size_name',
                        'product_sizes.selling_price as price'
                    )
                    ->get();
                    
                foreach ($cartItems as $item) {
                    $cartTotal += $item->price * $item->quantity;
                }
            }
        }

        $discountAmount = 0;
        $finalTotal = $cartTotal;
        $appliedVoucher = session('voucher');

        if ($appliedVoucher) {
            if ($cartTotal < $appliedVoucher['minimum_order']) {
                session()->forget('voucher');
                $appliedVoucher = null;
            } else {
                if ($appliedVoucher['discount_type'] === 'percent') {
                    $discountAmount = $cartTotal * ($appliedVoucher['discount_value'] / 100);
                    if ($appliedVoucher['maximum_discount'] && $discountAmount > $appliedVoucher['maximum_discount']) {
                        $discountAmount = $appliedVoucher['maximum_discount'];
                    }
                } else {
                    $discountAmount = $appliedVoucher['discount_value'];
                }
                
                if ($discountAmount > $cartTotal) {
                    $discountAmount = $cartTotal;
                }
                
                $finalTotal = $cartTotal - $discountAmount;
            }
        }

        $availableVouchers = DB::table('vouchers')
            ->where('status', 1)
            ->whereRaw('used < quantity')
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->get();

        return view('customer.checkout', compact('user', 'customerProfile', 'addresses', 'cartItems', 'cartTotal', 'discountAmount', 'finalTotal', 'appliedVoucher', 'availableVouchers'));
    }

    public function applyVoucher(Request $request)
    {
        $code = $request->input('code');
        
        if (!$code) {
            return back()->with('error', 'Vui lòng nhập mã giảm giá.');
        }

        $voucher = DB::table('vouchers')->where('code', $code)->first();

        if (!$voucher) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        if (!$voucher->status) {
            return back()->with('error', 'Mã giảm giá đã bị vô hiệu hóa.');
        }

        if ($voucher->start_date && now() < $voucher->start_date) {
            return back()->with('error', 'Mã giảm giá chưa đến thời gian áp dụng.');
        }

        if ($voucher->end_date && now() > $voucher->end_date) {
            return back()->with('error', 'Mã giảm giá đã hết hạn.');
        }

        if ($voucher->used >= $voucher->quantity) {
            return back()->with('error', 'Mã giảm giá đã hết lượt sử dụng.');
        }

        $userId = session('user_id');
        $user = \App\Models\User::find($userId);
        $customerProfile = DB::table('customer_profiles')->where('user_id', $user->id)->first();
        $cartTotal = 0;
        
        if ($customerProfile) {
            $cart = DB::table('carts')->where('customer_id', $customerProfile->id)->first();
            if ($cart) {
                $cartItems = DB::table('cart_items')
                    ->join('product_sizes', 'cart_items.product_size_id', '=', 'product_sizes.id')
                    ->where('cart_items.cart_id', $cart->id)
                    ->select('cart_items.quantity', 'product_sizes.selling_price as price')
                    ->get();
                foreach ($cartItems as $item) {
                    $cartTotal += $item->price * $item->quantity;
                }
            }
        }

        if ($cartTotal < $voucher->minimum_order) {
            return back()->with('error', 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->minimum_order, 0, ',', '.') . ' VNĐ.');
        }

        session([
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'maximum_discount' => $voucher->maximum_discount,
                'minimum_order' => $voucher->minimum_order
            ]
        ]);

        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function addToCart(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $user = \App\Models\User::find($userId);
        
        $customerProfile = DB::table('customer_profiles')->where('user_id', $user->id)->first();
        if (!$customerProfile) {
            $profileId = DB::table('customer_profiles')->insertGetId([
                'user_id' => $user->id,
                'full_name' => $user->username,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $customerProfile = DB::table('customer_profiles')->where('id', $profileId)->first();
        }

        $cart = DB::table('carts')->where('customer_id', $customerProfile->id)->first();
        if (!$cart) {
            $cartId = DB::table('carts')->insertGetId([
                'customer_id' => $customerProfile->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $cart = DB::table('carts')->where('id', $cartId)->first();
        }

        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');
        $quantity = $request->input('quantity', 1);

        $productSizeQuery = DB::table('product_sizes')->where('product_id', $productId);
        if ($sizeId) {
            $productSizeQuery->where('size_id', $sizeId);
        } else {
            $productSizeQuery->where('is_default', 1);
        }
        $productSize = $productSizeQuery->first();

        if (!$productSize) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $existingItem = DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->where('product_size_id', $productSize->id)
            ->first();

        if ($existingItem) {
            DB::table('cart_items')->where('id', $existingItem->id)->update([
                'quantity' => $existingItem->quantity + $quantity,
                'updated_at' => now()
            ]);
        } else {
            DB::table('cart_items')->insert([
                'cart_id' => $cart->id,
                'product_size_id' => $productSize->id,
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $cartCount = DB::table('cart_items')->where('cart_id', $cart->id)->sum('quantity');

        return response()->json(['success' => true, 'cartCount' => $cartCount]);
    }

    public function updateQuantity(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập'], 401);
        }

        $cartItemId = $request->input('cart_item_id');
        $action = $request->input('action');

        $item = DB::table('cart_items')->where('id', $cartItemId)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ'], 404);
        }

        $newQuantity = $item->quantity;
        if ($action === 'increase') {
            $newQuantity++;
        } elseif ($action === 'decrease') {
            $newQuantity--;
        }

        if ($newQuantity > 0) {
            DB::table('cart_items')->where('id', $cartItemId)->update([
                'quantity' => $newQuantity,
                'updated_at' => now()
            ]);
        } else {
            DB::table('cart_items')->where('id', $cartItemId)->delete();
        }

        return response()->json(['success' => true, 'new_quantity' => $newQuantity]);
    }

    public function placeOrder(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để đặt hàng.');
        }

        $user = \App\Models\User::find($userId);
        $customerProfile = DB::table('customer_profiles')->where('user_id', $user->id)->first();
        if (!$customerProfile) {
            return back()->with('error', 'Hồ sơ khách hàng không hợp lệ.');
        }

        $receiverName = $request->input('receiver_name');
        $receiverPhone = $request->input('receiver_phone');
        $shippingAddress = $request->input('shipping_address');
        $distanceKm = (float) $request->input('distance_km', 0);

        if (!$receiverName || !$receiverPhone || !$shippingAddress) {
            return back()->with('error', 'Vui lòng nhập đầy đủ thông tin giao hàng.');
        }
        
        if ($distanceKm > 10) {
            return back()->with('error', 'Khoảng cách giao hàng vượt quá 10km. Cửa hàng không thể hỗ trợ giao đơn hàng này.');
        }

        $addressId = null;
        $existingAddress = DB::table('customer_addresses')
            ->where('customer_id', $customerProfile->id)
            ->where('address', $shippingAddress)
            ->where('receiver_phone', $receiverPhone)
            ->first();

        if ($existingAddress) {
            $addressId = $existingAddress->id;
        } else {
            $addressId = DB::table('customer_addresses')->insertGetId([
                'customer_id' => $customerProfile->id,
                'receiver_name' => $receiverName,
                'receiver_phone' => $receiverPhone,
                'address' => $shippingAddress,
                'province' => '',
                'district' => '',
                'ward' => '',
                'is_default' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $paymentMethod = $request->input('payment', 'cash');
        $validPayments = ['cash', 'momo', 'vnpay', 'bank'];
        if (!in_array($paymentMethod, $validPayments)) {
            $paymentMethod = 'cash';
        }

        $cart = DB::table('carts')->where('customer_id', $customerProfile->id)->first();
        if (!$cart) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $cartItems = DB::table('cart_items')
            ->join('product_sizes', 'cart_items.product_size_id', '=', 'product_sizes.id')
            ->where('cart_items.cart_id', $cart->id)
            ->select('cart_items.*', 'product_sizes.selling_price as price')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item->price * $item->quantity;
        }

        $discountAmount = 0;
        $shippingFee = (float)$request->input('shipping_fee', 0);
        $appliedVoucher = session('voucher');
        $voucherId = null;

        if ($appliedVoucher && $cartTotal >= $appliedVoucher['minimum_order']) {
            $voucherId = $appliedVoucher['id'];
            if ($appliedVoucher['discount_type'] === 'percent') {
                $discountAmount = $cartTotal * ($appliedVoucher['discount_value'] / 100);
                if ($appliedVoucher['maximum_discount'] && $discountAmount > $appliedVoucher['maximum_discount']) {
                    $discountAmount = $appliedVoucher['maximum_discount'];
                }
            } else {
                $discountAmount = $appliedVoucher['discount_value'];
            }
            if ($discountAmount > $cartTotal) {
                $discountAmount = $cartTotal;
            }
        }

        $finalTotal = $cartTotal - $discountAmount + $shippingFee;

        DB::beginTransaction();
        try {
            $orderCode = 'ORD-' . strtoupper(uniqid());

            $orderId = DB::table('orders')->insertGetId([
                'order_code' => $orderCode,
                'customer_id' => $customerProfile->id,
                'address_id' => $addressId,
                'shipper_id' => null,
                'voucher_id' => $voucherId,
                'subtotal' => $cartTotal,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $finalTotal,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'note' => $request->input('note', ''),
                'ordered_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($cartItems as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            if ($voucherId) {
                DB::table('vouchers')->where('id', $voucherId)->increment('used');
            }

            DB::table('cart_items')->where('cart_id', $cart->id)->delete();

            DB::commit();

            session()->forget('voucher');
            
            return redirect('/customer/orders')->with('success', 'Đặt hàng thành công! Mã đơn hàng của bạn là ' . $orderCode);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại sau.');
        }
    }
}
