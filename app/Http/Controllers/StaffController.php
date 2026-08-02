<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        if (!check_permission('view_dashboard')) {
            return redirect('/login');
        }

        $pendingOrders = Order::where('status', 'pending')
            ->with(['customer.user', 'items.productSize.product', 'items.productSize.size', 'address'])
            ->orderByDesc('ordered_at')
            ->get();

        $preparingOrders = Order::where('status', 'preparing')
            ->with(['customer.user', 'items.productSize.product', 'items.productSize.size'])
            ->orderByDesc('ordered_at')
            ->get();

        $todayCompleted = Order::where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();

        return view('staff.dashboard', compact('pendingOrders', 'preparingOrders', 'todayCompleted'));
    }

    public function confirm($id)
    {
        if (!check_permission('view_dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json(['error' => 'Đơn hàng không ở trạng thái chờ xác nhận.'], 400);
        }

        $order->status = 'preparing';
        $order->save();

        return response()->json(['success' => true, 'message' => "Đơn hàng #{$order->order_code} đã được xác nhận và chuyển sang pha chế!"]);
    }

    public function complete($id)
    {
        if (!check_permission('view_dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::findOrFail($id);

        if ($order->status !== 'preparing') {
            return response()->json(['error' => 'Đơn hàng chưa ở trạng thái đang pha chế.'], 400);
        }

        $order->status = 'shipping';
        $order->save();

        return response()->json(['success' => true, 'message' => "Đơn hàng #{$order->order_code} đã sẵn sàng giao!"]);
    }
}
