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
}
