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
            return redirect('/login/admin')->with('error', __('Tài khoản của bạn không có quyền truy cập.'));
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

        $readyOrders = Order::where(function($q) {
                $q->where('order_status', 'READY_FOR_DELIVERY')->orWhere('status', 'ready_for_delivery');
            })
            ->with(['customer.user'])
            ->orderByDesc('updated_at')
            ->get();

        $todayCompleted = Order::where(function($q) {
                $q->where('order_status', 'COMPLETED')->orWhere('status', 'completed');
            })
            ->whereDate('updated_at', today())
            ->count();

        return view('staff.dashboard', compact('pendingOrders', 'preparingOrders', 'readyOrders', 'todayCompleted'));
    }

    public function confirm($id)
    {
        if (!check_permission('update_orders')) {
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
        }

        $order = Order::findOrFail($id);

        $order->order_status = 'PREPARING';
        $order->status = 'preparing';
        $order->save();

        $order->deductInventory();

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
            return response()->json(['error' => 'Không có quyền truy cập.'], 401);
        }

        $order = Order::findOrFail($id);

        if ($order->order_type === 'AT_TABLE') {
            $order->order_status = 'COMPLETED';
            $order->status = 'completed';
            $order->completed_at = now();
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => 'PREPARING',
                'new_status' => 'COMPLETED',
                'changed_by' => session('user_id'),
                'note' => 'Đơn tại bàn đã pha chế xong và hoàn thành',
            ]);
            
            \Illuminate\Support\Facades\DB::table('payments')
                ->where('order_id', $order->id)
                ->where('payment_status', 'PENDING')
                ->update(['payment_status' => 'COMPLETED']);

            $code = $order->code ?? $order->order_code ?? $order->id;
            return response()->json(['success' => true, 'message' => "Đơn hàng #{$code} tại bàn đã hoàn thành!"]);
        } else {
            $order->order_status = 'READY_FOR_DELIVERY';
            $order->status = 'ready_for_delivery';
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => 'PREPARING',
                'new_status' => 'READY_FOR_DELIVERY',
                'changed_by' => session('user_id'),
                'note' => 'Đơn hàng đã hoàn thành pha chế, chờ shipper',
            ]);

            $code = $order->code ?? $order->order_code ?? $order->id;
            return response()->json(['success' => true, 'message' => "Đơn hàng #{$code} đã sẵn sàng giao!"]);
        }
    }
}
