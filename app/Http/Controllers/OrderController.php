<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function customerOrders()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return redirect('/');
        }

        $orders = DB::table('orders')
            ->where('customer_id', $customerProfile->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewedItems = [];
        $reviewedOrderIds = [];
        if (\Illuminate\Support\Facades\Schema::hasTable('product_reviews')) {
            $reviews = DB::table('product_reviews')
                ->where('user_id', $userId)
                ->select('order_id', 'product_id')
                ->get();
                
            $reviewedItems = $reviews->map(function ($review) {
                    return $review->order_id . '_' . $review->product_id;
                })->toArray();
                
            $reviewedOrderIds = $reviews->pluck('order_id')->unique()->toArray();
        }

        $allItems = collect();

        foreach ($orders as $order) {
            $order->items = DB::table('order_items')
                ->leftJoin('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                ->leftJoin('products', function($join) {
                    $join->on('product_sizes.product_id', '=', 'products.id')
                         ->orWhereRaw('order_items.product_name = products.name');
                })
                ->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
                ->where('order_items.order_id', $order->id)
                ->select('order_items.*', 'products.name as product_name', 'products.image as product_image', 'sizes.name as size_name', 'products.id as product_id')
                ->get();

            foreach ($order->items as $item) {
                $item->is_reviewed = in_array($order->id . '_' . $item->product_id, $reviewedItems);
                $allItems->push($item);
            }

            $order->address = DB::table('customer_addresses')
                ->where('id', $order->address_id ?? 0)
                ->orWhere('address', $order->delivery_address ?? '')
                ->first();
                
            $order->shipper = null;
            if (isset($order->shipper_id) && $order->shipper_id) {
                $order->shipper = DB::table('shipper_profiles')
                    ->join('users', 'shipper_profiles.user_id', '=', 'users.id')
                    ->where('shipper_profiles.id', $order->shipper_id)
                    ->select('users.name as name')
                    ->first();
            }

            $order->payment = DB::table('payments')
                ->where('order_id', $order->id)
                ->first();
        }

        $allItemIds = $allItems->pluck('id')->toArray();
        if (!empty($allItemIds)) {
            $allToppings = DB::table('order_item_toppings')
                ->join('toppings', 'order_item_toppings.topping_id', '=', 'toppings.id')
                ->whereIn('order_item_toppings.order_item_id', $allItemIds)
                ->select('order_item_toppings.*', 'toppings.name as topping_name')
                ->get()
                ->groupBy('order_item_id');

            foreach ($allItems as $item) {
                $item->toppings = $allToppings->get($item->id, collect());
            }
        } else {
            foreach ($allItems as $item) {
                $item->toppings = collect();
            }
        }



        $activeOrders = $orders->filter(function ($order) {
            $status = strtolower($order->status ?? $order->order_status ?? '');
            return !in_array($status, ['completed', 'cancelled']);
        });

        $historyOrders = $orders->filter(function ($order) {
            $status = strtolower($order->status ?? $order->order_status ?? '');
            return in_array($status, ['completed', 'cancelled']);
        });

        return view('customer.orders', compact('activeOrders', 'historyOrders', 'orders', 'reviewedOrderIds'));
    }

    public function placeOrder(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $request->validate([
            'receiver_name'   => ['required', 'string', 'max:255'],
            'receiver_phone'  => ['required', 'string', 'max:20'],
            'address'         => ['required', 'string'],
            'payment_method'  => ['required', 'in:cash,momo,vnpay,bank,vietqr'],
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

        $createdOrderCode = null;

        DB::transaction(function () use ($request, $profile, $cartItems, $userId, $checkoutItemIds, &$createdOrderCode) {
            // Format full address
            $fullAddress = trim("{$request->address}");
            if ($request->ward) {
                $fullAddress .= ", {$request->ward}";
            }
            $fullAddress .= ", {$request->district}, {$request->province}";

            $isDefault = $request->has('set_default') ? 1 : 0;
            
            if ($isDefault) {
                CustomerAddress::where('customer_id', $profile->id)
                    ->update(['is_default' => 0]);
            }

            // Check if exact address already exists for this user
            $existingAddress = CustomerAddress::where('customer_id', $profile->id)
                ->where('address', $request->address)
                ->where('ward', $request->ward)
                ->where('district', $request->district)
                ->where('province', $request->province)
                ->where('receiver_phone', $request->receiver_phone)
                ->first();

            $deliveryLat = $request->input('delivery_latitude');
            $deliveryLon = $request->input('delivery_longitude');

            if ($existingAddress) {
                $customerAddress = $existingAddress;
                $updateData = [];
                if (!$existingAddress->is_saved) {
                    $updateData['is_saved'] = 1; // Always save to history
                }
                if ($isDefault && !$existingAddress->is_default) {
                    $updateData['is_default'] = 1;
                }
                if ($existingAddress->receiver_name !== $request->receiver_name) {
                    $updateData['receiver_name'] = $request->receiver_name;
                }
                if ($deliveryLat && $deliveryLon && ($existingAddress->latitude != $deliveryLat || $existingAddress->longitude != $deliveryLon)) {
                    $updateData['latitude'] = $deliveryLat;
                    $updateData['longitude'] = $deliveryLon;
                }
                if (!empty($updateData)) {
                    $existingAddress->update($updateData);
                }
            } else {
                $customerAddress = CustomerAddress::create([
                    'customer_id'    => $profile->id,
                    'receiver_name'  => $request->receiver_name,
                    'receiver_phone' => $request->receiver_phone,
                    'address'        => $request->address,
                    'ward'           => $request->ward,
                    'district'       => $request->district,
                    'province'       => $request->province,
                    'latitude'       => $deliveryLat,
                    'longitude'      => $deliveryLon,
                    'is_default'     => $isDefault,
                    'is_saved'       => 1, // Always save
                ]);
            }

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }

            // ---- Tính phí ship theo khoảng cách thực tế ----
            $clientDistance = (float) $request->input('distance_km', 0);
            $clientFee      = (float) $request->input('shipping_fee', 0);
            
            $deliveryLat = $request->input('delivery_latitude');
            $deliveryLon = $request->input('delivery_longitude');
            
            $deliveryService = app(\App\Services\DeliveryService::class);
            $storeLat = \App\Models\Setting::get('store_lat');
            $storeLon = \App\Models\Setting::get('store_lon');
            
            $distanceKm = $clientDistance;
            $shippingFee = $clientFee;
            $routeDurationMinutes = null;
            $distanceMethod = null;
            
            if ($storeLat && $storeLon && $deliveryLat && $deliveryLon) {
                // Tái tính toán khoảng cách
                $calculatedRoute = $deliveryService->calculateRoute($storeLon, $storeLat, $deliveryLon, $deliveryLat);
                if (isset($calculatedRoute['distance_km'])) {
                    $distanceKm = $calculatedRoute['distance_km'];
                    $routeDurationMinutes = $calculatedRoute['duration_minutes'] ?? null;
                    $distanceMethod = $calculatedRoute['method'] ?? null;
                }
            }
            
            // Tái tính toán phí dựa trên khoảng cách (chống gian lận)
            if ($distanceKm > 0) {
                $calculatedFee = $deliveryService->calculateShippingFee($distanceKm);
                // Cho phép sai số do làm tròn (VD: 1000đ)
                if (abs($calculatedFee - $clientFee) <= 2000) {
                    $shippingFee = $clientFee;
                } else {
                    $shippingFee = $calculatedFee;
                }
            } else {
                if ($clientFee == 0) {
                     $shippingFee = \App\Models\Setting::get('base_shipping_fee', 15000);
                }
            }
            // ------------------------------------------------

            $tax = 0; // Không tính thuế trong tổng
            
            $appliedVoucher = session('applied_voucher');
            $discount = 0;
            $voucherId = null;
            if ($appliedVoucher) {
                $discount = $appliedVoucher['discount_amount'];
                $voucherId = $appliedVoucher['id'];
                
                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
                
                // Increment voucher usage
                $voucher = \App\Models\Voucher::find($voucherId);
                if ($voucher) {
                    $voucher->increment('used_count');
                    $voucher->increment('used');
                }
            }
            
            $total = $subtotal + $shippingFee + $tax - $discount;

            $orderCode = 'ORD-' . strtoupper(uniqid());
            $createdOrderCode = $orderCode;

            $order = Order::create([
                'customer_id'     => $profile->id,
                'order_code'      => $orderCode,
                'order_source'    => 'WEBSITE',
                'order_type'      => 'DELIVERY',
                'order_status'    => 'PENDING',
                'receiver_name'   => $request->receiver_name,
                'receiver_phone'  => $request->receiver_phone,
                'address_id'      => $customerAddress->id,
                'delivery_address'=> $fullAddress,
                'delivery_latitude' => $deliveryLat,
                'delivery_longitude' => $deliveryLon,
                'customer_note'   => $request->note,
                'subtotal'        => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee'    => $shippingFee,
                'tax_amount'      => $tax,
                'distance_km'     => $distanceKm,
                'route_duration_minutes' => $routeDurationMinutes,
                'distance_method' => $distanceMethod,
                'total_amount'    => $total,
                'voucher_id'      => $voucherId,
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
                        \App\Models\OrderItemTopping::create([
                            'order_item_id' => $orderItem->id,
                            'topping_id'    => $topping['id'],
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
            session()->forget('applied_voucher');
        });

        if ($request->payment_method === 'vnpay') {
            $order = Order::where('order_code', $createdOrderCode)->first();
            return app(\App\Http\Controllers\PaymentController::class)->createVnpayPayment($order);
        }

        if ($request->payment_method === 'momo') {
            return redirect()->route('payment.fake.gateway', ['orderCode' => $createdOrderCode]);
        }

        if (in_array($request->payment_method, ['vietqr', 'bank'])) {
            return redirect()->route('customer.orders')->with([
                'success' => 'Đặt hàng thành công! Vui lòng quét mã VietQR để hoàn tất thanh toán.',
                'show_vietqr' => $createdOrderCode
            ]);
        }

        return redirect()->route('customer.orders')->with('success', 'Đặt hàng thành công!');
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) return back()->with('error', 'Không tìm thấy thông tin khách hàng');

        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerProfile->id)
            ->first();

        if (!$order) {
            return back()->with('error', 'Đơn hàng không tồn tại hoặc bạn không có quyền hủy.');
        }

        $status = strtolower($order->status ?? $order->order_status ?? '');
        if (in_array($status, ['preparing', 'shipping', 'delivering', 'completed', 'cancelled'])) {
            return back()->with('error', 'Đơn hàng đang chuẩn bị hoặc đã giao, không thể hủy.');
        }

        $cancelReason = request('cancel_reason') ?: 'Không có lý do';

        $order->status = 'cancelled';
        $order->order_status = 'CANCELLED';
        $order->cancel_reason = $cancelReason;
        $order->cancelled_at = now();
        $order->save();
        
        try {
            $user = DB::table('users')->where('id', $userId)->first();
            if ($user && $user->email) {
                \Illuminate\Support\Facades\Mail::to($user->email)
                    ->queue(new \App\Mail\OrderStatusChanged($order, $user->username, 'Đã bị hủy bởi khách hàng'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
        }

        return back()->with('cancel_success', 'Đã hủy đơn hàng! Cảm ơn bạn đã góp ý kiến.');
    }

    public function showReviewForm($orderId)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) return redirect('/')->with('error', 'Không tìm thấy hồ sơ');

        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerProfile->id)
            ->firstOrFail();
            
        $order->items = DB::table('order_items')
            ->leftJoin('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
            ->leftJoin('products', function ($join) {
                $join->on('product_sizes.product_id', '=', 'products.id')
                     ->orWhereRaw('order_items.product_name = products.name');
            })
            ->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
            ->where('order_items.order_id', $order->id)
            ->select('order_items.*', 'products.name as product_name', 'products.image as product_image', 'sizes.name as size_name', 'products.id as product_id')
            ->get()
            ->unique('product_id')
            ->values();

        // Check if status is completed
        $status = strtolower($order->order_status ?? $order->status ?? '');
        if ($status !== 'completed' && $status !== 'hoàn thành') {
            return redirect()->route('customer.orders')->with('error', 'Chỉ có thể đánh giá đơn hàng đã hoàn thành.');
        }

        return view('customer.review', compact('order'));
    }

    public function submitReview(Request $request, $orderId)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();

        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerProfile->id)
            ->firstOrFail();

        $status = strtolower($order->order_status ?? $order->status ?? '');
        if ($status !== 'completed' && $status !== 'hoàn thành') {
            return redirect()->route('customer.orders')->with('error', 'Đơn hàng chưa hoàn thành.');
        }

        $request->validate([
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:500',
            'shipper_rating' => 'nullable|integer|min:1|max:5'
        ]);

        foreach ($request->reviews as $reviewData) {
            $review = \App\Models\ProductReview::create([
                'user_id' => $userId,
                'product_id' => $reviewData['product_id'],
                'customer_id' => $customerProfile->id,
                'order_id' => $order->id,
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'],
                'status' => 'pending',
            ]);
            
            \App\Jobs\ProcessAiReview::dispatch($review);
        }

        if ($request->has('shipper_rating') && $request->shipper_rating) {
            $order->update(['shipper_rating' => $request->shipper_rating]);
            
            // Update ShipperProfile average rating
            if ($order->shipper_id) {
                $shipper = \App\Models\ShipperProfile::find($order->shipper_id);
                if ($shipper) {
                    $avgRating = \App\Models\Order::where('shipper_id', $shipper->id)
                        ->whereNotNull('shipper_rating')
                        ->avg('shipper_rating');
                    $shipper->rating = round($avgRating, 1);
                    $shipper->save();
                }
            }
        }

        return redirect()->route('customer.orders')->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }
}

