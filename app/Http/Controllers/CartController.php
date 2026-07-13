<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomerProfile;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getOrCreateCart()
    {
        $userId = session('user_id');
        if (!$userId) {
            return null;
        }

        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        // Try to get or create customer profile
        $profile = CustomerProfile::firstOrCreate(
            ['user_id' => $userId],
            [
                'full_name' => $user->username,
                'status' => true
            ]
        );

        // Get or create cart
        return Cart::firstOrCreate(['customer_id' => $profile->id]);
    }

    public function add(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Bạn cần đăng nhập để thêm vào giỏ hàng.'], 401);
        }

        $request->validate([
            'product_size_id' => ['required', 'integer', 'exists:product_sizes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->getOrCreateCart();
        if (!$cart) {
            return response()->json(['error' => 'Không thể tạo giỏ hàng.'], 500);
        }

        $productSizeId = $request->input('product_size_id');
        $quantity = $request->input('quantity', 1);

        // Check if item exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_size_id', $productSizeId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_size_id' => $productSizeId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_item_count' => $cart->items()->sum('quantity')
        ]);
    }

    public function update(Request $request, $id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'], // 0 means remove
        ]);

        $cart = $this->getOrCreateCart();
        if (!$cart) {
            return response()->json(['error' => 'Giỏ hàng không hợp lệ'], 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if (!$cartItem) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);
        }

        $quantity = (int) $request->input('quantity');

        if ($quantity <= 0) {
            $cartItem->delete();
            $message = 'Đã xóa sản phẩm khỏi giỏ hàng';
        } else {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            $message = 'Đã cập nhật số lượng';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_item_count' => $cart->items()->sum('quantity'),
            'total_price' => $this->calculateTotals($cart)
        ]);
    }

    public function remove($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = $this->getOrCreateCart();
        if (!$cart) {
            return response()->json(['error' => 'Giỏ hàng không hợp lệ'], 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if (!$cartItem) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_item_count' => $cart->items()->sum('quantity'),
            'total_price' => $this->calculateTotals($cart)
        ]);
    }

    public function checkout()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để truy cập giỏ hàng.');
        }

        $cart = $this->getOrCreateCart();
        $cartItems = [];
        $subtotal = 0;

        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['productSize.product', 'productSize.size'])
                ->get();

            foreach ($cartItems as $item) {
                if ($item->productSize) {
                    $subtotal += $item->productSize->selling_price * $item->quantity;
                }
            }
        }

        $deliveryFee = $subtotal > 0 ? 15000 : 0; // Simple delivery fee in VND (15,000 đ)
        $tax = $subtotal * 0.08; // 8% VAT
        $total = $subtotal + $deliveryFee + $tax;

        // Fetch user's addresses if exists
        $addresses = DB::table('customer_addresses')
            ->where('customer_id', $cart ? $cart->customer_id : 0)
            ->get();

        return view('customer.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'tax', 'total', 'addresses'));
    }

    private function calculateTotals($cart)
    {
        $cartItems = CartItem::where('cart_id', $cart->id)->with('productSize')->get();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            if ($item->productSize) {
                $subtotal += $item->productSize->selling_price * $item->quantity;
            }
        }
        $deliveryFee = $subtotal > 0 ? 15000 : 0;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $deliveryFee + $tax;

        return [
            'subtotal' => number_format($subtotal, 0, ',', '.') . ' đ',
            'delivery_fee' => number_format($deliveryFee, 0, ',', '.') . ' đ',
            'tax' => number_format($tax, 0, ',', '.') . ' đ',
            'total' => number_format($total, 0, ',', '.') . ' đ',
            'raw_total' => $total
        ];
    }
}
