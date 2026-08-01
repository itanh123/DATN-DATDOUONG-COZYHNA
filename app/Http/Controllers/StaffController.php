<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        if (!check_permission('view_dashboard')) {
            return redirect('/login');
        }

        $pendingOrders = Order::where('order_status', 'PENDING')
            ->with(['customer.user', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $preparingOrders = Order::where('order_status', 'PREPARING')
            ->with(['customer.user', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $shippingOrders = Order::where('order_status', 'DELIVERING')
            ->with(['customer.user', 'shipper'])
            ->orderByDesc('updated_at')
            ->get();

        $todayCompleted = Order::where('order_status', 'COMPLETED')
            ->whereDate('updated_at', today())
            ->count();

        return view('staff.dashboard', compact('pendingOrders', 'preparingOrders', 'shippingOrders', 'todayCompleted'));
    }

    public function confirm($id)
    {
        if (!check_permission('edit_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::findOrFail($id);

        if ($order->order_status !== 'PENDING') {
            return response()->json(['error' => 'Đơn hàng không ở trạng thái chờ xác nhận.'], 400);
        }

        $order->order_status = 'PREPARING';
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => 'PENDING',
            'new_status' => 'PREPARING',
            'changed_by' => session('user_id'),
            'note' => 'Nhân viên xác nhận đơn hàng',
        ]);

        return response()->json(['success' => true, 'message' => "Đơn hàng #{$order->code} đã được xác nhận và chuyển sang pha chế!"]);
    }

    public function complete($id)
    {
        if (!check_permission('edit_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::findOrFail($id);

        if ($order->order_status !== 'PREPARING') {
            return response()->json(['error' => 'Đơn hàng chưa ở trạng thái đang pha chế.'], 400);
        }

        $order->order_status = 'DELIVERING';
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => 'PREPARING',
            'new_status' => 'DELIVERING',
            'changed_by' => session('user_id'),
            'note' => 'Đơn hàng đã hoàn thành pha chế, chờ shipper',
        ]);

        return response()->json(['success' => true, 'message' => "Đơn hàng #{$order->code} đã sẵn sàng giao!"]);
    }
}
