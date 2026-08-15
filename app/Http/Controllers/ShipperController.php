<?php

namespace App\Http\Controllers;

use App\Models\OrderStatusHistory;
use App\Models\Order;
use App\Models\ShipperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipperController extends Controller
{
    /**
     * Kiểm tra quyền Shipper và lấy profile của Shipper hiện tại.
     */
    private function getShipperProfile()
    {
        $userId = session('user_id');
        if (!$userId) {
            return null;
        }

        return ShipperProfile::where('user_id', $userId)->first();
    }

    /**
     * Trang portal chính của Shipper — tải dữ liệu cho 3 tab.
     */
    public function portal()
    {
        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return redirect('/login/admin')->with('error', 'Vui lòng đăng nhập với tài khoản Shipper.');
        }

        // Tab "Available": đơn hàng đã sẵn sàng giao, chưa có shipper nhận
        $availableOrders = Order::where('order_status', 'READY_FOR_DELIVERY')
            ->whereNull('shipper_id')
            ->with(['customer.user', 'items.toppings.topping', 'address'])
            ->orderByDesc('updated_at')
            ->get();

        // Tab "Active": đơn hàng shipper này đang giao
        $activeOrders = Order::where('order_status', 'DELIVERING')
            ->where('shipper_id', $shipper->id)
            ->with(['customer.user', 'items.toppings.topping', 'address'])
            ->orderByDesc('updated_at')
            ->get();

        // Tab "History": lịch sử giao hàng
        $historyItems = OrderStatusHistory::where('changed_by', session('user_id'))
            ->whereIn('new_status', ['DELIVERING', 'COMPLETED'])
            ->with(['order.customer.user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        // Thống kê nhanh
        $totalDeliveries  = $shipper->total_deliveries;
        $todayDeliveries  = OrderStatusHistory::where('changed_by', session('user_id'))
            ->where('new_status', 'COMPLETED')
            ->whereDate('created_at', today())
            ->count();

        return view('shipper.delivery_portal', compact(
            'shipper',
            'availableOrders',
            'activeOrders',
            'historyItems',
            'totalDeliveries',
            'todayDeliveries'
        ));
    }

    /**
     * Shipper nhận đơn hàng.
     * POST /shipper/orders/{id}/accept
     */
    public function acceptOrder(Request $request, $orderId)
    {
        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
        }

        try {
            DB::transaction(function () use ($orderId, $shipper) {
                $order = Order::where('id', $orderId)
                    ->where('order_status', 'READY_FOR_DELIVERY')
                    ->whereNull('shipper_id')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Gắn shipper vào đơn hàng
                $order->shipper_id = $shipper->id;
                $order->order_status = 'DELIVERING';
                $order->status = 'shipping';
                $order->save();

                // Tạo bản ghi history
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => 'READY_FOR_DELIVERY',
                    'new_status' => 'DELIVERING',
                    'changed_by' => session('user_id'),
                    'note'       => 'Shipper đã nhận đơn hàng.',
                ]);
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Rất tiếc, đơn này đã có shipper khác nhanh tay nhận!'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bạn đã nhận đơn hàng thành công!',
        ]);
    }

    /**
     * Lấy danh sách đơn hàng có sẵn dưới dạng HTML để polling (cập nhật tự động).
     * GET /shipper/orders/available-html
     */
    public function availableOrdersHtml()
    {
        // Giải phóng session lock sớm để các request khác (như nhận đơn) không bị block
        session()->save();

        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $availableOrders = Order::where('order_status', 'READY_FOR_DELIVERY')
            ->whereNull('shipper_id')
            ->with(['customer.user', 'items.toppings.topping', 'address', 'items.productSize.product', 'items.productSize.size'])
            ->orderByDesc('updated_at')
            ->get();

        return view('shipper.partials.available_orders_list', compact('availableOrders'))->render();
    }

    /**
     * Shipper cập nhật trạng thái giao hàng.
     * POST /shipper/orders/{id}/status
     */
    public function updateStatus(Request $request, $orderId)
    {
        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
        }

        $request->merge(['status' => strtoupper($request->status)]);

        $request->validate([
            'status' => ['required', 'in:PICKED_UP,DELIVERING,COMPLETED,FAILED'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::where('id', $orderId)
            ->where('shipper_id', $shipper->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $order, $shipper) {
            if ($request->status === 'COMPLETED') {
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => $order->order_status,
                    'new_status' => 'COMPLETED',
                    'changed_by' => session('user_id'),
                    'note'       => $request->note ?? 'Giao hàng thành công',
                ]);

                $order->order_status = 'COMPLETED';
                $order->completed_at = now();
                $order->save();

                \App\Models\Payment::where('order_id', $order->id)
                    ->where('payment_status', 'PENDING')
                    ->update(['payment_status' => 'COMPLETED']);

                $shipper->increment('total_deliveries');
            }
            elseif ($request->status === 'FAILED') {
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => $order->order_status,
                    'new_status' => 'PREPARING',
                    'changed_by' => session('user_id'),
                    'note'       => $request->note ?? 'Giao hàng thất bại',
                ]);
                $order->order_status = 'PREPARING';
                $order->shipper_id = null;
                $order->save();
            }
            elseif ($request->status === 'PICKED_UP') {
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => $order->order_status,
                    'new_status' => $order->order_status,
                    'changed_by' => session('user_id'),
                    'note'       => $request->note ?? 'Đã lấy hàng và bắt đầu đi giao',
                ]);
            }
            elseif ($request->status === 'DELIVERING') {
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'old_status' => $order->order_status,
                    'new_status' => $order->order_status,
                    'changed_by' => session('user_id'),
                    'note'       => $request->note ?? 'Đang trên đường giao đến khách hàng',
                ]);
            }
        });

        $statusLabels = [
            'COMPLETED'  => 'Giao hàng thành công',
            'FAILED'     => 'Giao hàng thất bại, đơn đã được chuyển về kho',
            'PICKED_UP'  => 'Đã cập nhật trạng thái lấy hàng',
            'DELIVERING' => 'Đã cập nhật trạng thái đang giao',
        ];

        return response()->json([
            'success' => true,
            'message' => $statusLabels[$request->status] ?? 'Đã cập nhật trạng thái.',
        ]);
    }

    /**
     * Trang lịch sử giao hàng và doanh thu
     */
    public function history()
    {
        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return redirect('/login/admin')->with('error', 'Vui lòng đăng nhập với tài khoản Shipper.');
        }

        $orders = Order::where('shipper_id', $shipper->id)
            ->where('order_status', 'COMPLETED')
            ->with(['customer.user', 'address'])
            ->orderByDesc('completed_at')
            ->paginate(20);

        $totalRevenue = Order::where('shipper_id', $shipper->id)
            ->where('order_status', 'COMPLETED')
            ->sum('shipping_fee');

        return view('shipper.history', compact('shipper', 'orders', 'totalRevenue'));
    }

    public function reviews()
    {
        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return redirect('/login/admin')->with('error', 'Vui lòng đăng nhập với tài khoản Shipper.');
        }

        $ordersWithReviews = Order::where('shipper_id', $shipper->id)
            ->whereNotNull('shipper_rating')
            ->with(['customer.user'])
            ->orderByDesc('completed_at')
            ->paginate(20);

        return view('shipper.reviews', compact('shipper', 'ordersWithReviews'));
    }
}
