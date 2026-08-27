<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSize;
use App\Models\Topping;

class OrderController extends Controller
{
    /**
     * Danh sách lịch sử đơn hàng
     */
    public function index(Request $request)
    {
        $user = clone $request->user();
        $user->load('customerProfile');
        $customerProfile = $user->customerProfile;

        if (!$customerProfile) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy hồ sơ khách hàng'], 404);
        }

        $orders = Order::where('customer_id', $customerProfile->id)
            ->with(['items.productSize.product', 'items.productSize.size', 'voucher'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show(Request $request, $id)
    {
        $user = clone $request->user();
        $user->load('customerProfile');
        
        $order = Order::with(['items.product', 'items.productSize.size', 'items.toppings.topping', 'address'])
            ->where('id', $id)
            ->where('customer_id', $user->customerProfile->id ?? -1)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Đặt hàng từ App
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'address' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.product_size_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'in:cash,vnpay,momo,bank'
        ]);

        $user = $request->user();
        $customerProfile = $user->customerProfile;

        if (!$customerProfile) {
            return response()->json(['success' => false, 'message' => 'Hồ sơ khách hàng không hợp lệ.'], 400);
        }

        $items = $request->input('items');
        $cartTotal = 0;
        $orderItemsData = [];

        // Validate and calculate real prices from Database
        foreach ($items as $itemReq) {
            $productSize = ProductSize::find($itemReq['product_size_id']);
            if (!$productSize || $productSize->product_id != $itemReq['product_id']) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không hợp lệ.'], 400);
            }

            $unitPrice = (float) $productSize->selling_price;
            $lineTotal = $unitPrice * $itemReq['quantity'];
            
            $itemToppingsData = [];
            if (isset($itemReq['toppings']) && is_array($itemReq['toppings'])) {
                foreach ($itemReq['toppings'] as $topReq) {
                    $topping = Topping::find($topReq['topping_id']);
                    if ($topping) {
                        $topUnitPrice = (float) $topping->price;
                        $topQuantity = $topReq['quantity'] ?? 1;
                        $topTotal = $topUnitPrice * $topQuantity;
                        
                        $lineTotal += $topTotal * $itemReq['quantity'];
                        
                        $itemToppingsData[] = [
                            'topping_id' => $topping->id,
                            'quantity' => $topQuantity,
                            'unit_price' => $topUnitPrice,
                            'total_price' => $topTotal
                        ];
                    }
                }
            }

            $cartTotal += $lineTotal;
            $orderItemsData[] = [
                'product_id' => $itemReq['product_id'],
                'product_size_id' => $itemReq['product_size_id'],
                'quantity' => $itemReq['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $lineTotal,
                'toppings' => $itemToppingsData
            ];
        }

        $minOrderAmount = (float) \App\Models\Setting::get('min_order_amount', 0);
        if ($cartTotal < $minOrderAmount) {
            return response()->json(['success' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu (' . number_format($minOrderAmount, 0, ',', '.') . ' đ).'], 400);
        }

        $shippingFee = (float)$request->input('shipping_fee', 0);
        $discountAmount = 0;
        $finalTotal = $cartTotal - $discountAmount + $shippingFee;

        DB::beginTransaction();
        try {
            $orderCode = 'ORD-' . strtoupper(uniqid());

            $addressId = DB::table('customer_addresses')->insertGetId([
                'customer_id' => $customerProfile->id,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'address' => $request->address,
                'province' => $request->province,
                'district' => $request->district,
                'ward' => $request->ward ?? '',
                'is_default' => 0,
                'is_saved' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_id' => $customerProfile->id,
                'address_id' => $addressId,
                'subtotal' => $cartTotal,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $finalTotal,
                'payment_method' => $request->input('payment_method', 'cash'),
                'order_status' => 'PENDING',
                'status' => 'pending',
                'order_type' => 'DELIVERY',
                'note' => $request->input('note', ''),
                'distance_km' => $request->input('distance_km', 0),
                'ordered_at' => now()
            ]);

            foreach ($orderItemsData as $itemData) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'product_size_id' => $itemData['product_size_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['total_price'],
                ]);

                foreach ($itemData['toppings'] as $topData) {
                    DB::table('order_item_toppings')->insert([
                        'order_item_id' => $orderItem->id,
                        'topping_id' => $topData['topping_id'],
                        'quantity' => $topData['quantity'],
                        'unit_price' => $topData['unit_price'],
                        'total_price' => $topData['total_price'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công!',
                'data' => [
                    'order_id' => $order->id,
                    'order_code' => $orderCode,
                    'total_amount' => $finalTotal
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
}
