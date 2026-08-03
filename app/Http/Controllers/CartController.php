<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getCartItems()
    {
        return session('cart', []);
    }

    private function saveCartItems($cartItems)
    {
        session(['cart' => $cartItems]);
    }

    public function add(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Bạn cần đăng nhập để thêm vào giỏ hàng.'], 401);
        }

        $request->validate([
            'product_id'      => ['nullable', 'integer', 'exists:products,id'],
            'product_size_id' => ['nullable', 'integer', 'exists:product_sizes,id'],
            'unit_price'      => ['nullable', 'numeric', 'min:0'],
            'quantity'        => ['required', 'integer', 'min:1'],
            'topping_ids'     => ['nullable', 'array'],
            'topping_ids.*'   => ['integer', 'exists:toppings,id'],
            'toppings'        => ['nullable', 'array'],
            'toppings.*'      => ['integer', 'exists:toppings,id'],
        ]);

        $productSizeId = $request->input('product_size_id');
        $productId     = $request->input('product_id');
        $quantity      = (int) $request->input('quantity', 1);

        if ($productSizeId) {
            $ps = ProductSize::findOrFail($productSizeId);
            $productId = $ps->product_id;
            $unitPrice = (float) $ps->selling_price;
        } elseif ($productId) {
            $unitPrice = (float) $request->input('unit_price', 0);
        } else {
            return response()->json(['error' => 'Thiếu thông tin sản phẩm.'], 422);
        }

        $toppingIds = $request->input('topping_ids') ?? $request->input('toppings', []);
        $toppingIds = array_map('intval', $toppingIds);
        $toppings = [];
        if (!empty($toppingIds)) {
            $dbToppings = \App\Models\Topping::whereIn('id', $toppingIds)->get();
            foreach ($dbToppings as $top) {
                $toppings[] = [
                    'id' => $top->id,
                    'name' => $top->name,
                    'price' => (float) $top->price,
                ];
                $unitPrice += (float) $top->price;
            }
        }

        sort($toppingIds);
        $toppingStr = empty($toppingIds) ? 'none' : implode(',', $toppingIds);

        $cartItems = $this->getCartItems();
        $cartItemId = $productId . '_' . ($productSizeId ?? 'none') . '_t_' . $toppingStr;

        if (isset($cartItems[$cartItemId])) {
            $cartItems[$cartItemId]['quantity'] += $quantity;
        } else {
            $cartItems[$cartItemId] = [
                'id'              => $cartItemId,
                'product_id'      => $productId,
                'product_size_id' => $productSizeId,
                'quantity'        => $quantity,
                'unit_price'      => $unitPrice,
                'toppings'        => $toppings,
            ];
        }

        $this->saveCartItems($cartItems);

        return response()->json([
            'success'         => true,
            'message'         => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_item_count' => array_sum(array_column($cartItems, 'quantity')),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);
        
        $cartItems = $this->getCartItems();
        
        if (!isset($cartItems[$id])) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);
        }

        $quantity = (int) $request->input('quantity');
        if ($quantity <= 0) {
            unset($cartItems[$id]);
            $message = 'Đã xóa sản phẩm khỏi giỏ hàng';
        } else {
            $cartItems[$id]['quantity'] = $quantity;
            $message = 'Đã cập nhật số lượng';
        }

        $this->saveCartItems($cartItems);

        return response()->json([
            'success'         => true,
            'message'         => $message,
            'cart_item_count' => array_sum(array_column($cartItems, 'quantity')),
            'total_price'     => $this->calcTotals($cartItems),
        ]);
    }

    public function remove($id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cartItems = $this->getCartItems();
        
        if (!isset($cartItems[$id])) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);
        }

        unset($cartItems[$id]);
        $this->saveCartItems($cartItems);

        return response()->json([
            'success'         => true,
            'message'         => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_item_count' => array_sum(array_column($cartItems, 'quantity')),
            'total_price'     => $this->calcTotals($cartItems),
        ]);
    }

    public function index()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để truy cập giỏ hàng.');
        }

        $cartItemsRaw = $this->getCartItems();
        $cartItems = collect();
        $subtotal = 0;

        foreach ($cartItemsRaw as $item) {
            $product = Product::find($item['product_id']);
            $productSize = $item['product_size_id'] ? ProductSize::with('size')->find($item['product_size_id']) : null;
            
            if ($product) {
                $obj = new \stdClass();
                $obj->id = $item['id'];
                $obj->product_id = $item['product_id'];
                $obj->product_size_id = $item['product_size_id'];
                $obj->quantity = $item['quantity'];
                $obj->unit_price = $item['unit_price'];
                $obj->product = $product;
                $obj->productSize = $productSize;
                $obj->toppings = $item['toppings'] ?? [];
                
                $cartItems->push($obj);
                $subtotal += $item['unit_price'] * $item['quantity'];
            }
        }

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    public function initCheckout(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'string'
        ]);

        session(['checkout_items' => $request->selected_items]);
        
        return response()->json(['success' => true, 'redirect' => route('customer.checkout')]);
    }

    public function checkout()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để truy cập giỏ hàng.');
        }

        $checkoutItemIds = session('checkout_items', []);
        if (empty($checkoutItemIds)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán.');
        }

        $cartItemsRaw = $this->getCartItems();
        $cartItems = collect();
        $subtotal = 0;

        foreach ($cartItemsRaw as $item) {
            if (!in_array($item['id'], $checkoutItemIds)) continue;

            $product = Product::find($item['product_id']);
            $productSize = $item['product_size_id'] ? ProductSize::with('size')->find($item['product_size_id']) : null;
            
            if ($product) {
                $obj = new \stdClass();
                $obj->id = $item['id'];
                $obj->product_id = $item['product_id'];
                $obj->product_size_id = $item['product_size_id'];
                $obj->quantity = $item['quantity'];
                $obj->unit_price = $item['unit_price'];
                $obj->product = $product;
                $obj->productSize = $productSize;
                $obj->toppings = $item['toppings'] ?? [];
                
                $cartItems->push($obj);
                $subtotal += $item['unit_price'] * $item['quantity'];
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm được chọn.');
        }

        $deliveryFee = $subtotal > 0 ? 15000 : 0;
        $tax         = round($subtotal * 0.08);
        $total       = $subtotal + $deliveryFee + $tax;

        $userId = session('user_id');
        $profile = CustomerProfile::where('user_id', $userId)->first();
        $addresses = collect();
        if ($profile) {
            $addresses = DB::table('customer_addresses')
                ->where('customer_id', $profile->id)
                ->whereNull('deleted_at')
                ->get();
        }

        return view('customer.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'tax', 'total', 'addresses'));
    }

    private function calcTotals($cartItems): array
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }
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
