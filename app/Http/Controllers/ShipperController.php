<?php

namespace App\Http\Controllers;

use App\Models\Orders\OrderStatusHistory;
use App\Models\Orders\Order;
use App\Models\Profiles\ShipperProfile;
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
            return redirect('/login')->with('error', 'Vui lòng đăng nhập với tài khoản Shipper.');
        }

        // Tab "Available": đơn hàng đã sẵn sàng giao, chưa có shipper nhận
        $availableOrders = Order::where('order_status', 'DELIVERING')
            ->whereNull('shipper_id')
            ->with(['customer.user', 'items'])
            ->orderByDesc('updated_at')
            ->get();

        // Tab "Active": đơn hàng shipper này đang giao
        $activeOrders = Order::where('order_status', 'DELIVERING')
            ->where('shipper_id', $shipper->id)
            ->with(['customer.user', 'items'])
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
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        DB::transaction(function () use ($orderId, $shipper) {
            $order = Order::where('id', $orderId)
                ->where('order_status', 'DELIVERING')
                ->whereNull('shipper_id')
                ->lockForUpdate()
                ->firstOrFail();

            // Gắn shipper vào đơn hàng
            $order->shipper_id = $shipper->id;
            $order->save();

            // Tạo bản ghi history
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'old_status' => 'DELIVERING',
                'new_status' => 'DELIVERING',
                'changed_by' => session('user_id'),
                'note'       => 'Shipper đã nhận đơn hàng.',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Bạn đã nhận đơn hàng thành công!',
        ]);
    }

    /**
     * Shipper cập nhật trạng thái giao hàng.
     * POST /shipper/orders/{id}/status
     */
    public function updateStatus(Request $request, $orderId)
    {
        $shipper = $this->getShipperProfile();
        if (!$shipper) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => ['required', 'in:COMPLETED,FAILED'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::where('id', $orderId)
            ->where('shipper_id', $shipper->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $order, $shipper) {
            // Nếu hoàn thành → cập nhật đơn hàng & thống kê shipper
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

                // Cập nhật trạng thái thanh toán nếu là tiền mặt
                \App\Models\Orders\Payment::where('order_id', $order->id)
                    ->where('payment_status', 'PENDING')
                    ->update(['payment_status' => 'COMPLETED']);

                $shipper->increment('total_deliveries');
            }

            // Nếu giao thất bại → trả đơn về available (xóa shipper_id)
            if ($request->status === 'FAILED') {
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
        });

        $statusLabels = [
            'COMPLETED'  => 'Giao hàng thành công',
            'FAILED'     => 'Giao hàng thất bại, đơn đã được chuyển về kho',
        ];

        return response()->json([
            'success' => true,
            'message' => $statusLabels[$request->status] ?? 'Đã cập nhật trạng thái.',
        ]);
    }
}
