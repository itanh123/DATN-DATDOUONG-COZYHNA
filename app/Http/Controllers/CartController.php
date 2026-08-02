<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ---------------------------------------------------------------
    // Internal helper: get or create cart for logged-in customer
    // ---------------------------------------------------------------
    private function getOrCreateCart(): ?Cart
    {
        $userId = session('user_id');
        if (!$userId) return null;

        $user = User::find($userId);
        if (!$user) return null;

        $profile = CustomerProfile::firstOrCreate(
            ['user_id' => $userId],
            ['full_name' => $user->username, 'status' => true]
        );

        return Cart::firstOrCreate(['customer_id' => $profile->id]);
    }

    // ---------------------------------------------------------------
    // POST /cart/add
    // Accepts EITHER:
    //   { product_size_id, quantity }          ← product with sizes
    //   { product_id, unit_price, quantity }   ← product without sizes
    // ---------------------------------------------------------------
    public function add(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Bạn cần đăng nhập để thêm vào giỏ hàng.'], 401);
        }

        // --- Validate ---
        $request->validate([
            'product_id'      => ['nullable', 'integer', 'exists:products,id'],
            'product_size_id' => ['nullable', 'integer', 'exists:product_sizes,id'],
            'unit_price'      => ['nullable', 'numeric', 'min:0'],
            'quantity'        => ['required', 'integer', 'min:1'],
        ]);

        $productSizeId = $request->input('product_size_id');
        $productId     = $request->input('product_id');
        $quantity      = (int) $request->input('quantity', 1);

        // --- Resolve product + price ---
        if ($productSizeId) {
            // Product WITH a size
            $ps = ProductSize::with('product')->findOrFail($productSizeId);
            $productId = $ps->product_id;
            $unitPrice = (float) $ps->selling_price;
        } elseif ($productId) {
            // Product WITHOUT a size
            $product   = Product::findOrFail($productId);
            $unitPrice = (float) ($request->input('unit_price') ?? $product->base_price ?? 0);
        } else {
            return response()->json(['error' => 'Thiếu thông tin sản phẩm.'], 422);
        }

        $cart = $this->getOrCreateCart();
        if (!$cart) {
            return response()->json(['error' => 'Không thể tạo giỏ hàng.'], 500);
        }

        // --- Upsert cart item ---
        // Unique key: (cart_id, product_id, product_size_id)
        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('product_size_id', $productSizeId) // both nullable → works
            ->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->save();
        } else {
            CartItem::create([
                'cart_id'         => $cart->id,
                'product_id'      => $productId,
                'product_size_id' => $productSizeId,
                'quantity'        => $quantity,
                'unit_price'      => $unitPrice,
            ]);
        }

        return response()->json([
            'success'         => true,
            'message'         => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_item_count' => $cart->items()->sum('quantity'),
        ]);
    }

    // ---------------------------------------------------------------
    // POST /cart/update/{id}
    // ---------------------------------------------------------------
    public function update(Request $request, $id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);

        $cart = $this->getOrCreateCart();
        if (!$cart) return response()->json(['error' => 'Giỏ hàng không hợp lệ'], 404);

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if (!$cartItem) return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);

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
            'success'         => true,
            'message'         => $message,
            'cart_item_count' => $cart->items()->sum('quantity'),
            'total_price'     => $this->calcTotals($cart),
        ]);
    }

    // ---------------------------------------------------------------
    // POST /cart/remove/{id}
    // ---------------------------------------------------------------
    public function remove($id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = $this->getOrCreateCart();
        if (!$cart) return response()->json(['error' => 'Giỏ hàng không hợp lệ'], 404);

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if (!$cartItem) return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);

        $cartItem->delete();

        return response()->json([
            'success'         => true,
            'message'         => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_item_count' => $cart->items()->sum('quantity'),
            'total_price'     => $this->calcTotals($cart),
        ]);
    }

    // ---------------------------------------------------------------
    // GET /customer/checkout
    // ---------------------------------------------------------------
    public function checkout()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để truy cập giỏ hàng.');
        }

        $cart      = $this->getOrCreateCart();
        $cartItems = collect();
        $subtotal  = 0;

        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->with(['product', 'productSize.size'])
                ->get();

            foreach ($cartItems as $item) {
                $subtotal += $item->unit_price * $item->quantity;
            }
        }

        $deliveryFee = $subtotal > 0 ? 15000 : 0;
        $tax         = round($subtotal * 0.08);
        $total       = $subtotal + $deliveryFee + $tax;

        $addresses = collect();
        if ($cart) {
            $addresses = DB::table('customer_addresses')
                ->where('customer_id', $cart->customer_id)
                ->whereNull('deleted_at')
                ->get();
        }

        return view('customer.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'tax', 'total', 'addresses'));
    }

    // ---------------------------------------------------------------
    // Private: calculate totals for a cart
    // ---------------------------------------------------------------
    private function calcTotals(Cart $cart): array
    {
        $items    = CartItem::where('cart_id', $cart->id)->get();
        $subtotal = $items->sum(fn ($i) => $i->unit_price * $i->quantity);
        $fee      = $subtotal > 0 ? 15000 : 0;
        $tax      = round($subtotal * 0.08);
        $total    = $subtotal + $fee + $tax;

        return [
            'subtotal'    => number_format($subtotal, 0, ',', '.') . ' đ',
            'delivery'    => number_format($fee, 0, ',', '.') . ' đ',
            'tax'         => number_format($tax, 0, ',', '.') . ' đ',
            'total'       => number_format($total, 0, ',', '.') . ' đ',
            'raw_total'   => $total,
        ];
    }
}
