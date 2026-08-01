<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\ShipperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // Require admin permission
        if (!check_permission('view_orders')) {
            return redirect('/login');
        }
        $query = Order::with(['customer.user', 'items.productSize.product', 'items.productSize.size', 'shipper']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('order_status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('receiver_name', 'LIKE', "%{$search}%")
                  ->orWhere('receiver_phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer.user', function ($subq) use ($search) {
                      $subq->where('username', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderByDesc('created_at')->paginate(20);

        // Stats
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingPrep = Order::whereIn('order_status', ['PENDING', 'PREPARING'])->count();
        $delivering = Order::where('order_status', 'DELIVERING')->count();
        
        $totalMinutes = 0;
        $completedOrdersCount = Order::where('order_status', 'COMPLETED')->whereDate('created_at', today())->count();
        if ($completedOrdersCount > 0) {
            $completedOrders = Order::where('order_status', 'COMPLETED')->whereDate('created_at', today())->get();
            foreach ($completedOrders as $o) {
                $totalMinutes += $o->created_at->diffInMinutes($o->completed_at ?? $o->updated_at);
            }
            $avgFulfillment = round($totalMinutes / $completedOrdersCount) . 'm';
        } else {
            $avgFulfillment = '--';
        }

        $shippers = ShipperProfile::with('user')->get();

        return view('admin.orders', compact('orders', 'todayOrders', 'pendingPrep', 'delivering', 'avgFulfillment', 'shippers'));
    }

    public function show($id)
    {
        if (!check_permission('view_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::with([
            'customer.user', 
            'items.productSize.product', 
            'items.productSize.size', 
            'shipper.user'
        ])->findOrFail($id);

        $histories = OrderStatusHistory::where('order_id', $id)
                        ->with('changedBy')
                        ->orderByDesc('created_at')
                        ->get();

        return response()->json([
            'order' => $order,
            'histories' => $histories
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!check_permission('edit_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => 'required|in:PENDING,PREPARING,DELIVERING,COMPLETED,CANCELLED',
        ]);

        $order = Order::findOrFail($id);

        DB::transaction(function () use ($request, $order) {
            $oldStatus = $order->order_status;
            
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'changed_by' => session('user_id'),
                'note' => 'Admin cập nhật trạng thái',
            ]);

            $order->order_status = $request->status;
            if ($request->status === 'COMPLETED') {
                $order->completed_at = now();
                
                // Đồng bộ thống kê shipper
                if ($order->shipper_id && $oldStatus !== 'COMPLETED') {
                    $shipper = \App\Models\ShipperProfile::find($order->shipper_id);
                    if ($shipper) {
                        $shipper->increment('total_deliveries');
                    }
                }
                
                // Cập nhật trạng thái thanh toán nếu là tiền mặt
                \App\Models\Payment::where('order_id', $order->id)
                    ->where('payment_status', 'PENDING')
                    ->update(['payment_status' => 'COMPLETED']);

            } else if ($request->status === 'CANCELLED') {
                $order->cancelled_at = now();
            }
            $order->save();
        });

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }

    public function assignShipper(Request $request, $id)
    {
        if (!check_permission('edit_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'shipper_id' => 'nullable|exists:shipper_profiles,id',
        ]);

        $order = Order::findOrFail($id);
        
        if (!in_array($order->order_status, ['PREPARING', 'DELIVERING'])) {
            return response()->json(['error' => 'Chỉ có thể gán Shipper khi đơn đang xử lý hoặc đang giao'], 400);
        }

        $order->shipper_id = $request->shipper_id;
        if ($request->shipper_id && $order->order_status === 'PREPARING') {
            $order->order_status = 'DELIVERING';
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => 'PREPARING',
                'new_status' => 'DELIVERING',
                'changed_by' => session('user_id'),
                'note' => 'Admin gán Shipper: ' . $request->shipper_id,
            ]);
        }

        $order->save();

        return response()->json(['success' => true, 'message' => 'Gán Shipper thành công']);
    }
}
