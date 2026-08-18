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

    private function checkCartStock(array $simulatedCartItems)
    {
        $requiredIngredients = [];

        foreach ($simulatedCartItems as $item) {
            if (empty($item['product_size_id']) || empty($item['quantity'])) continue;
            
            $recipe = \App\Models\Recipe::where('product_size_id', $item['product_size_id'])
                ->with('ingredients.ingredient')
                ->first();

            if (!$recipe) continue;

            foreach ($recipe->ingredients as $ri) {
                if (!$ri->ingredient) continue;
                
                $ingredientId = $ri->ingredient_id;
                if (!isset($requiredIngredients[$ingredientId])) {
                    $requiredIngredients[$ingredientId] = [
                        'name' => $ri->ingredient->name,
                        'required' => 0,
                        'stock' => $ri->ingredient->current_stock,
                    ];
                }
                $requiredIngredients[$ingredientId]['required'] += ($ri->quantity * $item['quantity']);
            }
        }

        foreach ($requiredIngredients as $ing) {
            if ($ing['required'] > $ing['stock']) {
                return "Không đủ nguyên liệu: {$ing['name']} (Cần: " . round($ing['required'], 2) . ", Tồn: " . round($ing['stock'], 2) . ")";
            }
        }

        return true;
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

        $stockCheck = $this->checkCartStock($cartItems);
        if ($stockCheck !== true) {
            return response()->json(['error' => $stockCheck], 400);
        }

        $this->saveCartItems($cartItems);

        return response()->json([
            'success'         => true,
            'message'         => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_item_count' => count($cartItems),
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
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

        $stockCheck = $this->checkCartStock($cartItems);
        if ($stockCheck !== true) {
            return response()->json(['error' => $stockCheck], 400);
        }

        $this->saveCartItems($cartItems);

        return response()->json([
            'success'         => true,
            'message'         => $message,
            'cart_item_count' => count($cartItems),
            'total_price'     => $this->calcTotals($cartItems),
        ]);
    }

    public function remove($id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
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
            'cart_item_count' => count($cartItems),
            'total_price'     => $this->calcTotals($cartItems),
        ]);
    }

    public function updateVariant(Request $request, $id)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_size_id' => ['nullable', 'integer', 'exists:product_sizes,id'],
            'topping_ids'     => ['nullable', 'array'],
            'topping_ids.*'   => ['integer', 'exists:toppings,id'],
        ]);

        $cartItems = $this->getCartItems();
        if (!isset($cartItems[$id])) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ'], 404);
        }

        $oldItem = $cartItems[$id];
        $productId = $oldItem['product_id'];
        $quantity = $oldItem['quantity'];

        $productSizeId = $request->input('product_size_id');
        
        $unitPrice = 0;
        if ($productSizeId) {
            $ps = ProductSize::findOrFail($productSizeId);
            $unitPrice = (float) $ps->selling_price;
        } else {
            // If no size id passed, attempt to find a default or fallback to 0
            $ps = ProductSize::where('product_id', $productId)->first();
            if ($ps) {
                $productSizeId = $ps->id;
                $unitPrice = (float) $ps->selling_price;
            }
        }

        $toppingIds = $request->input('topping_ids', []);
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

        $newItemId = $productId . '_' . ($productSizeId ?? 'none') . '_t_' . $toppingStr;

        // Remove old item
        unset($cartItems[$id]);

        // Add new item (merge quantity if it exists)
        if (isset($cartItems[$newItemId])) {
            $cartItems[$newItemId]['quantity'] += $quantity;
        } else {
            $cartItems[$newItemId] = [
                'id'              => $newItemId,
                'product_id'      => $productId,
                'product_size_id' => $productSizeId,
                'quantity'        => $quantity,
                'unit_price'      => $unitPrice,
                'toppings'        => $toppings,
            ];
        }

        $stockCheck = $this->checkCartStock($cartItems);
        if ($stockCheck !== true) {
            return response()->json(['error' => $stockCheck], 400);
        }

        $this->saveCartItems($cartItems);

        return response()->json([
            'success'         => true,
            'message'         => 'Đã cập nhật tùy chọn',
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
            $product = Product::with(['productSizes.size', 'toppings'])->find($item['product_id']);
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

        $appliedVoucher = session('applied_voucher');
        $discountAmount = 0;
        if ($appliedVoucher) {
            $discountAmount = $appliedVoucher['discount_amount'];
            $voucher = \App\Models\Voucher::find($appliedVoucher['id']);
            if ($voucher && $voucher->minimum_order && $subtotal < $voucher->minimum_order) {
                session()->forget('applied_voucher');
                $appliedVoucher = null;
                $discountAmount = 0;
            } else if ($voucher) {
                if ($voucher->discount_type === 'percent') {
                    $discountAmount = ($subtotal * $voucher->discount_value) / 100;
                    if ($voucher->maximum_discount && $discountAmount > $voucher->maximum_discount) {
                        $discountAmount = $voucher->maximum_discount;
                    }
                } else {
                    $discountAmount = $voucher->discount_value;
                }
                
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }
                
                $appliedVoucher['discount_amount'] = $discountAmount;
                session(['applied_voucher' => $appliedVoucher]);
            }
        }

        $availableVouchers = \Illuminate\Support\Facades\DB::table('vouchers')
            ->where('status', 1)
            ->whereRaw('used < quantity')
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->get();

        $allToppings = \App\Models\Topping::where('status', true)->get();

        return view('customer.cart', compact('cartItems', 'subtotal', 'appliedVoucher', 'discountAmount', 'availableVouchers', 'allToppings'));
    }

    public function initCheckout(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
        }

        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'string'
        ]);

        session(['checkout_items' => $request->selected_items]);
        
        $isTableOrder = session('is_table_order', false);
        $redirectUrl = $isTableOrder ? '/table/order/confirm' : route('customer.checkout');
        
        return response()->json([
            'success' => true, 
            'redirect' => $redirectUrl,
            'is_table_order' => $isTableOrder
        ]);
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
        $minOrderAmount = (float) \App\Models\Setting::get('min_order_amount', 0);
        if ($subtotal < $minOrderAmount) {
            return redirect()->route('cart.index')->with('error', 'Đơn hàng chưa đạt giá trị tối thiểu để giao hàng (' . number_format($minOrderAmount, 0, ',', '.') . ' đ). Vui lòng mua thêm.');
        }

        $deliveryFee = 0; // Sẽ được tính lại bằng JS ở frontend khi có địa chỉ
        $tax         = 0;

        $appliedVoucher = session('applied_voucher');
        $discountAmount = 0;
        if ($appliedVoucher) {
            $discountAmount = $appliedVoucher['discount_amount'];
            // Revalidate voucher minimum order just in case
            $voucher = \App\Models\Voucher::find($appliedVoucher['id']);
            if ($voucher && $voucher->minimum_order && $subtotal < $voucher->minimum_order) {
                session()->forget('applied_voucher');
                $appliedVoucher = null;
                $discountAmount = 0;
            } else if ($voucher) {
                // Recalculate discount based on current subtotal
                if ($voucher->discount_type === 'percent') {
                    $discountAmount = ($subtotal * $voucher->discount_value) / 100;
                    if ($voucher->maximum_discount && $discountAmount > $voucher->maximum_discount) {
                        $discountAmount = $voucher->maximum_discount;
                    }
                } else {
                    $discountAmount = $voucher->discount_value;
                }
                
                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }
                
                // Update session
                $appliedVoucher['discount_amount'] = $discountAmount;
                session(['applied_voucher' => $appliedVoucher]);
            } else {
                session()->forget('applied_voucher');
                $appliedVoucher = null;
            }
        }

        $total       = $subtotal + $deliveryFee + $tax - $discountAmount;

        $userId = session('user_id');
        $profile = CustomerProfile::where('user_id', $userId)->first();
        $addresses = collect();
        if ($profile) {
            $addresses = DB::table('customer_addresses')
                ->where('customer_id', $profile->id)
                ->whereNull('deleted_at')
                ->get();
        }
        $feePerKm = (float) \App\Models\Setting::get('fee_per_km', 0);
        $maxRadius = (float) \App\Models\Setting::get('max_delivery_radius', 0);
        $baseFee = (float) \App\Models\Setting::get('base_shipping_fee', 15000);
        $storeProvince = \App\Models\Setting::get('store_province', 'Hà Nội');
        $storeDistrict = \App\Models\Setting::get('store_district', '');
        $storeWard = \App\Models\Setting::get('store_ward', '');
        $storeSpecificAddress = \App\Models\Setting::get('store_specific_address', '');
        $storeLat = \App\Models\Setting::get('store_lat', '');
        $storeLon = \App\Models\Setting::get('store_lon', '');

        return view('customer.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'tax', 'discountAmount', 'appliedVoucher', 'total', 'addresses', 'feePerKm', 'maxRadius', 'baseFee', 'storeProvince', 'storeDistrict', 'storeWard', 'storeSpecificAddress', 'storeLat', 'storeLon'));
    }

    private function calcTotals($cartItems): array
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }
        $fee      = $subtotal > 0 ? 15000 : 0;
        $tax      = 0;
        $total    = $subtotal + $fee + $tax;

        return [
            'subtotal'    => number_format($subtotal, 0, ',', '.') . ' đ',
            'delivery'    => number_format($fee, 0, ',', '.') . ' đ',
            'tax'         => number_format($tax, 0, ',', '.') . ' đ',
            'total'       => number_format($total, 0, ',', '.') . ' đ',
            'raw_total'   => $total,
        ];
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal'     => 'required|numeric'
        ]);

        $code = $request->voucher_code;
        $subtotal = $request->subtotal;

        $voucher = \App\Models\Voucher::where('code', $code)->where('status', true)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa.']);
        }

        if ($voucher->start_date && now()->lt($voucher->start_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá chưa đến thời gian áp dụng.']);
        }

        if ($voucher->end_date && now()->gt($voucher->end_date)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn.']);
        }

        if ($voucher->quantity !== null && $voucher->used >= $voucher->quantity) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        if ($voucher->minimum_order && $subtotal < $voucher->minimum_order) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->minimum_order, 0, ',', '.') . ' đ để áp dụng mã này.']);
        }

        $discountAmount = 0;
        if ($voucher->discount_type === 'percent') {
            $discountAmount = ($subtotal * $voucher->discount_value) / 100;
            if ($voucher->maximum_discount && $discountAmount > $voucher->maximum_discount) {
                $discountAmount = $voucher->maximum_discount;
            }
        } else {
            $discountAmount = $voucher->discount_value;
        }

        // Save voucher to session
        session(['applied_voucher' => [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'discount_amount' => $discountAmount,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount_amount' => $discountAmount,
            'voucher_code' => $voucher->code
        ]);
    }

    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        return response()->json(['success' => true, 'message' => 'Đã gỡ mã giảm giá.']);
    }
}
