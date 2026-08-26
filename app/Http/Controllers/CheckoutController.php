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
                ->where('is_saved', 1)
                ->orderBy('is_default', 'desc')
                ->get();

            $cart = DB::table('carts')
                ->where('customer_id', $customerProfile->id)
                ->first();

            if ($cart) {
                $cartItemModels = \App\Models\CartItem::where('cart_id', $cart->id)
                    ->with(['product', 'productSize.size', 'toppings.topping'])
                    ->get();

                foreach ($cartItemModels as $model) {
                    $item = new \stdClass();
                    $item->cart_item_id = $model->id;
                    $item->quantity = $model->quantity;
                    $item->product_name = $model->product ? $model->product->name : 'Unknown';
                    $item->product_image = $model->product ? $model->product->image : '';
                    $item->size_name = $model->productSize && $model->productSize->size ? $model->productSize->size->name : 'N/A';
                    
                    // The price per unit should include the topping price per unit
                    $toppingUnitSum = $model->toppings->sum('unit_price');
                    $basePrice = $model->unit_price; 
                    // Fallback to selling_price if unit_price is 0
                    if ($basePrice == 0 && $model->productSize) {
                        $basePrice = $model->productSize->selling_price;
                    }
                    $item->price = $basePrice + $toppingUnitSum;
                    
                    // Store the toppings just in case the view needs them
                    $item->toppings = $model->toppings;
                    
                    $cartItems[] = $item;
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

        $usedVoucherIds = [];
        if ($customerProfile) {
            $usedVoucherIds = DB::table('orders')
                ->where('customer_id', $customerProfile->id)
                ->whereNotNull('voucher_id')
                ->pluck('voucher_id')
                ->toArray();
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
            ->where('minimum_order', '<=', $cartTotal)
            ->get()
            ->filter(function ($voucher) use ($usedVoucherIds) {
                if ($voucher->is_one_time_use && in_array($voucher->id, $usedVoucherIds)) {
                    return false;
                }
                return true;
            })
            ->values();

        return view('customer.checkout', compact('user', 'customerProfile', 'addresses', 'cartItems', 'cartTotal', 'discountAmount', 'finalTotal', 'appliedVoucher', 'availableVouchers'));
    }

    public function applyVoucher(Request $request)
    {
        $code = $request->input('code');
        
        if (!$code) {
            return back()->with('voucher_error', 'Vui lòng nhập mã giảm giá.');
        }

        $voucher = DB::table('vouchers')->where('code', $code)->first();

        if (!$voucher) {
            return back()->with('voucher_error', 'Mã giảm giá không tồn tại.');
        }

        if (!$voucher->status) {
            return back()->with('voucher_error', 'Mã giảm giá đã bị vô hiệu hóa.');
        }

        if ($voucher->start_date && now() < $voucher->start_date) {
            return back()->with('voucher_error', 'Mã giảm giá chưa đến thời gian áp dụng.');
        }

        if ($voucher->end_date && now() > $voucher->end_date) {
            return back()->with('voucher_error', 'Mã giảm giá đã hết hạn.');
        }

        if ($voucher->used >= $voucher->quantity) {
            return back()->with('voucher_error', 'Mã giảm giá đã hết lượt sử dụng.');
        }

        $userId = session('user_id');
        $user = \App\Models\User::find($userId);

        if ($user && $user->is_restricted) {
            return back()->with('voucher_error', 'Tài khoản của bạn đang bị hạn chế và không thể sử dụng mã giảm giá.');
        }

        $customerProfile = DB::table('customer_profiles')->where('user_id', $user->id)->first();
        
        if ($voucher->is_one_time_use && $customerProfile) {
            $hasUsed = DB::table('orders')
                ->where('customer_id', $customerProfile->id)
                ->where('voucher_id', $voucher->id)
                ->exists();
            if ($hasUsed) {
                return back()->with('voucher_error', 'Bạn đã sử dụng mã giảm giá này rồi (mã này chỉ được áp dụng 1 lần cho mỗi tài khoản).');
            }
        }

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
            return back()->with('voucher_error', 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->minimum_order, 0, ',', '.') . ' VNĐ.');
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

        $toppingsInput = $request->input('toppings', []);
        $toppingsInput = array_map('intval', $toppingsInput);
        sort($toppingsInput);

        $existingItems = \App\Models\CartItem::where('cart_id', $cart->id)
            ->where('product_size_id', $productSize->id)
            ->with('toppings')
            ->get();

        $merged = false;
        foreach ($existingItems as $existing) {
            $itemToppings = $existing->toppings->pluck('topping_id')->toArray();
            sort($itemToppings);
            
            if ($itemToppings === $toppingsInput) {
                $existing->quantity += $quantity;
                $existing->save();
                $merged = true;
                break;
            }
        }

        if (!$merged) {
            $cartItem = \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'product_size_id' => $productSize->id,
                'quantity' => $quantity,
                'product_id' => $productId,
                'unit_price' => $productSize->selling_price,
            ]);

            foreach ($toppingsInput as $toppingId) {
                $topping = \App\Models\Topping::find($toppingId);
                if ($topping) {
                    $cartItem->toppings()->create([
                        'topping_id' => $topping->id,
                        'quantity' => 1,
                        'unit_price' => $topping->price,
                        'total_price' => $topping->price,
                    ]);
                }
            }
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
        
        $province = $request->input('province');
        $district = $request->input('district');
        $ward = $request->input('ward');
        $specificAddress = $request->input('address');

        if (!$receiverName || !$receiverPhone || !$province || !$district || !$specificAddress) {
            return back()->with('error', 'Vui lòng nhập đầy đủ thông tin giao hàng.');
        }

        $shippingAddress = "{$specificAddress}";
        if ($ward) {
            $shippingAddress .= ", {$ward}";
        }
        $shippingAddress .= ", {$district}, {$province}";
        $distanceKm = (float) $request->input('distance_km', 0);
        $maxRadius = (float) \App\Models\Setting::get('max_delivery_radius', 0);
        
        if ($maxRadius > 0 && $distanceKm > $maxRadius) {
            return back()->with('error', "Khoảng cách giao hàng ({$distanceKm}km) vượt quá bán kính cho phép ({$maxRadius}km).");
        }

        $addressId = $request->input('address_id');
        $isSaved = $request->has('save_address');

        if (!$addressId) {
            // Check if exact address already exists for this user (even if not explicitly "saved in book")
            $existingAddress = DB::table('customer_addresses')
                ->where('customer_id', $customerProfile->id)
                ->where('address', $specificAddress)
                ->where('ward', $ward)
                ->where('district', $district)
                ->where('province', $province)
                ->where('receiver_phone', $receiverPhone)
                ->first();

            if ($existingAddress) {
                $addressId = $existingAddress->id;
                if ($isSaved && !$existingAddress->is_saved) {
                    DB::table('customer_addresses')->where('id', $addressId)->update(['is_saved' => 1]);
                }
            } else {
                $addressId = DB::table('customer_addresses')->insertGetId([
                    'customer_id' => $customerProfile->id,
                    'receiver_name' => $receiverName,
                    'receiver_phone' => $receiverPhone,
                    'address' => $specificAddress,
                    'province' => $province,
                    'district' => $district,
                    'ward' => $ward,
                    'is_default' => 0,
                    'is_saved' => $isSaved ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $paymentMethod = $request->input('payment', 'cash');

        if ($user->is_restricted && $paymentMethod === 'cash') {
            return back()->with('error', 'Tài khoản của bạn đang bị hạn chế và không thể chọn phương thức thanh toán COD.');
        }

        $validPayments = ['cash', 'momo', 'vnpay', 'bank'];
        if (!in_array($paymentMethod, $validPayments)) {
            $paymentMethod = 'cash';
        }

        $cart = DB::table('carts')->where('customer_id', $customerProfile->id)->first();
        if (!$cart) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $cartItems = \App\Models\CartItem::where('cart_id', $cart->id)
            ->with(['toppings'])
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item->line_total;
        }

        $minOrderAmount = (float) \App\Models\Setting::get('min_order_amount', 0);
        if ($cartTotal < $minOrderAmount) {
            return back()->with('error', 'Đơn hàng chưa đạt giá trị tối thiểu để giao hàng (' . number_format($minOrderAmount, 0, ',', '.') . ' đ).');
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
                'delivery_latitude' => $request->input('delivery_latitude'),
                'delivery_longitude' => $request->input('delivery_longitude'),
                'route_duration_minutes' => $request->input('route_duration_minutes'),
                'distance_km' => $distanceKm,
                'ordered_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($cartItems as $item) {
                $orderItemId = DB::table('order_items')->insertGetId([
                    'order_id' => $orderId,
                    'product_id' => $item->product_id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->line_total,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                foreach ($item->toppings as $topping) {
                    DB::table('order_item_toppings')->insert([
                        'order_item_id' => $orderItemId,
                        'topping_id' => $topping->topping_id,
                        'quantity' => $topping->quantity,
                        'unit_price' => $topping->unit_price,
                        'total_price' => $topping->total_price,
                    ]);
                }
            }

            if ($voucherId) {
                DB::table('vouchers')->where('id', $voucherId)->increment('used');
            }

            DB::table('cart_items')->where('cart_id', $cart->id)->delete();

            DB::commit();

            session()->forget('voucher');
            
            try {
                if ($user && $user->email) {
                    $order = DB::table('orders')->where('id', $orderId)->first();
                    \Illuminate\Support\Facades\Mail::to($user->email)
                        ->queue(new \App\Mail\OrderStatusChanged($order, $user->username, 'Đặt hàng thành công (Đang chờ xác nhận)'));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
            }

            return redirect('/customer/orders')->with('success', 'Đặt hàng thành công! Mã đơn hàng của bạn là ' . $orderCode);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại sau.');
        }
    }
}
