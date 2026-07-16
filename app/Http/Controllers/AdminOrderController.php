<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        if (!check_permission('view_orders')) {
            return redirect('/login')->with('error', 'You do not have permission to access this page.');
        }

        $query = DB::table('orders')
            ->join('customer_profiles', 'orders.customer_id', '=', 'customer_profiles.id')
            ->join('users', 'customer_profiles.user_id', '=', 'users.id')
            ->leftJoin('customer_addresses', 'orders.address_id', '=', 'customer_addresses.id')
            ->select(
                'orders.*', 
                'users.username as customer_name',
                'customer_addresses.receiver_name',
                'customer_addresses.receiver_phone',
                'customer_addresses.address as shipping_address'
            );

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
        if (!check_permission('update_orders')) {
            return redirect('/login')->with('error', 'You do not have permission.');
        }
        $order = DB::table('orders')->where('id', $id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        $status = $request->input('status');
        if (in_array($status, ['pending', 'confirmed', 'preparing', 'shipping', 'completed', 'cancelled'])) {
            // Chỉ trừ nguyên liệu khi trạng thái chuyển thành "đang giao" (shipping)
            // và trạng thái cũ chưa phải là shipping/completed/cancelled
            if ($status === 'shipping' && !in_array($order->status, ['shipping', 'completed', 'cancelled'])) {
                $orderItems = DB::table('order_items')->where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    $recipe = \App\Models\Recipe::where('product_size_id', $item->product_size_id)->first();
                    if ($recipe) {
                        $recipeIngredients = \App\Models\RecipeIngredient::where('recipe_id', $recipe->id)->get();
                        foreach ($recipeIngredients as $ri) {
                            \App\Models\Ingredient::where('id', $ri->ingredient_id)
                                ->decrement('current_stock', $ri->quantity * $item->quantity);
                        }
                    }
                }
            }

            DB::table('orders')->where('id', $id)->update([
                'status' => $status,
                'updated_at' => now()
            ]);
            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
        }
        
        return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
    }
}
