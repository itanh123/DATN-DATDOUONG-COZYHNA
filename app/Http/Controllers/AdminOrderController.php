<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        if (!check_permission('manage_orders')) {
            return redirect('/login')->with('error', 'You do not have permission to access this page.');
        }

        $query = DB::table('orders')
            ->join('customer_profiles', 'orders.customer_id', '=', 'customer_profiles.id')
            ->join('users', 'customer_profiles.user_id', '=', 'users.id')
            ->select('orders.*', 'users.username as customer_name');

        // Apply filters
        $statusFilter = $request->input('status');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('orders.status', $statusFilter);
        }

        $dateFilter = $request->input('date');
        if ($dateFilter) {
            $query->whereDate('orders.created_at', $dateFilter);
        }

        $orders = $query->orderBy('orders.created_at', 'desc')->paginate(15);

        // Fetch items for each order
        foreach ($orders as $order) {
            $order->items = DB::table('order_items')
                ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                ->join('products', 'product_sizes.product_id', '=', 'products.id')
                ->where('order_items.order_id', $order->id)
                ->select('order_items.*', 'products.name as product_name')
                ->get();
        }

        // Stats
        $today = Carbon::today();
        
        $totalOrdersToday = DB::table('orders')->whereDate('created_at', $today)->count();
        $pendingPrep = DB::table('orders')->whereIn('status', ['pending', 'confirmed', 'preparing'])->count();
        $outForDelivery = DB::table('orders')->where('status', 'shipping')->count();
        
        $completedOrdersToday = DB::table('orders')
            ->where('status', 'completed')
            ->whereDate('created_at', $today)
            ->get();
            
        $totalMinutes = 0;
        $count = $completedOrdersToday->count();
        if ($count > 0) {
            foreach($completedOrdersToday as $o) {
                $created = Carbon::parse($o->created_at);
                $updated = Carbon::parse($o->updated_at);
                $totalMinutes += $updated->diffInMinutes($created);
            }
            $avgMinutes = round($totalMinutes / $count);
            $avgFulfillment = $avgMinutes . 'm';
        } else {
            $avgFulfillment = 'N/A';
        }

        return view('admin.orders', compact('orders', 'totalOrdersToday', 'pendingPrep', 'outForDelivery', 'avgFulfillment', 'statusFilter', 'dateFilter'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!check_permission('manage_orders')) {
            return redirect('/login')->with('error', 'You do not have permission.');
        }

        $status = $request->input('status');
        if (in_array($status, ['pending', 'confirmed', 'preparing', 'shipping', 'completed', 'cancelled'])) {
            DB::table('orders')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => now()
            ]);
            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
        }
        
        return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
    }
}
