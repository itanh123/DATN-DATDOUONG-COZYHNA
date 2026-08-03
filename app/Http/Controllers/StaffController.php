<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function dashboard()
    {
        if (!check_permission('view_dashboard')) {
            return redirect('/login');
        }

        $pendingOrders = Order::where(function($q) {
                $q->where('order_status', 'PENDING')->orWhere('status', 'pending');
            })
            ->with(['customer.user', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $preparingOrders = Order::where(function($q) {
                $q->where('order_status', 'PREPARING')->orWhere('status', 'preparing');
            })
            ->with(['customer.user', 'items'])
            ->orderByDesc('created_at')
            ->get();

        $shippingOrders = Order::where(function($q) {
                $q->where('order_status', 'DELIVERING')->orWhere('status', 'shipping');
            })
            ->with(['customer.user', 'shipper'])
            ->orderByDesc('updated_at')
            ->get();

        $todayCompleted = Order::where(function($q) {
                $q->where('order_status', 'COMPLETED')->orWhere('status', 'completed');
            })
            ->whereDate('updated_at', today())
            ->count();

        return view('staff.dashboard', compact('pendingOrders', 'preparingOrders', 'shippingOrders', 'todayCompleted'));
    }

    public function confirm($id)
    {
        if (!check_permission('update_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::findOrFail($id);

        $order->order_status = 'PREPARING';
        $order->status = 'preparing';
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => 'PENDING',
            'new_status' => 'PREPARING',
            'changed_by' => session('user_id'),
            'note' => 'Nhân viên xác nhận đơn hàng',
        ]);

        $code = $order->code ?? $order->order_code ?? $order->id;
        return response()->json(['success' => true, 'message' => "Đơn hàng #{$code} đã được xác nhận và chuyển sang pha chế!"]);
    }

    public function complete($id)
    {
        if (!check_permission('update_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::findOrFail($id);

        $order->order_status = 'DELIVERING';
        $order->status = 'shipping';
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => 'PREPARING',
            'new_status' => 'DELIVERING',
            'changed_by' => session('user_id'),
            'note' => 'Đơn hàng đã hoàn thành pha chế, chờ shipper',
        ]);

        $code = $order->code ?? $order->order_code ?? $order->id;
        return response()->json(['success' => true, 'message' => "Đơn hàng #{$code} đã sẵn sàng giao!"]);
    }
}
