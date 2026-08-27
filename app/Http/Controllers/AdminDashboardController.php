<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!check_permission('view_dashboard')) {
            return redirect('/login/admin')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        // 1. Doanh thu (Revenue)
        $revenue = DB::table('orders')
            ->where('order_status', 'COMPLETED')
            ->sum('total_amount');
            
        $lastMonthRevenue = DB::table('orders')
            ->where('order_status', 'COMPLETED')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_amount');
        
        $thisMonthRevenue = DB::table('orders')
            ->where('order_status', 'COMPLETED')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');
            
        $revenueChange = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : ($thisMonthRevenue > 0 ? 100 : 0);

        // 2. Tổng Đơn hàng (Total Orders)
        $totalOrders = DB::table('orders')->count();
        $lastMonthOrders = DB::table('orders')->whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
        $thisMonthOrders = DB::table('orders')->whereMonth('created_at', Carbon::now()->month)->count();
        $ordersChange = $lastMonthOrders > 0 ? (($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : ($thisMonthOrders > 0 ? 100 : 0);

        // 3. Khách hàng mới (New Customers)
        $newCustomers = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.code', 'customer')
            ->count();
            
        $lastMonthCustomers = DB::table('users')->join('roles', 'users.role_id', '=', 'roles.id')->where('roles.code', 'customer')->whereMonth('users.created_at', Carbon::now()->subMonth()->month)->count();
        $thisMonthCustomers = DB::table('users')->join('roles', 'users.role_id', '=', 'roles.id')->where('roles.code', 'customer')->whereMonth('users.created_at', Carbon::now()->month)->count();
        $customersChange = $lastMonthCustomers > 0 ? (($thisMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 : ($thisMonthCustomers > 0 ? 100 : 0);

        // 4. Sản phẩm đang bán (Active Products)
        $activeProducts = DB::table('products')->where('status', 'active')->count();
        $totalProductsDb = DB::table('products')->count();
        $activeProductsPercent = $totalProductsDb > 0 ? ($activeProducts / $totalProductsDb) * 100 : 0;

        // 5. Chart Data (Dynamic Range)
        $range = $request->query('range', '7'); // Default to 7
        
        $chartData = [];
        $chartLabels = [];
        
        if ($range === 'year') {
            // Data for each month of current year
            for ($i = 1; $i <= 12; $i++) {
                $monthRevenue = DB::table('orders')
                    ->where('order_status', 'COMPLETED')
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $i)
                    ->sum('total_amount');
                $chartLabels[] = 'Tháng ' . $i;
                $chartData[] = $monthRevenue;
            }
        } else {
            $days = (int) $range;
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $dailyRevenue = DB::table('orders')
                    ->where('order_status', 'COMPLETED')
                    ->whereDate('created_at', $date)
                    ->sum('total_amount');
                $chartLabels[] = Carbon::now()->subDays($i)->format('d/m');
                $chartData[] = $dailyRevenue;
            }
        }

        // 6. Sản phẩm bán chạy (Top Performing Products)
        $topProducts = DB::table('order_items')
            ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'COMPLETED')
            ->select(
                'products.name',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(COALESCE(order_items.total_price, order_items.final_price * order_items.quantity, order_items.unit_price * order_items.quantity)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->take(3)
            ->get();

        // 7. Đơn hàng gần đây (Recent Orders)
        $recentOrders = DB::table('orders')
            ->join('customer_profiles', 'orders.customer_id', '=', 'customer_profiles.id')
            ->join('users', 'customer_profiles.user_id', '=', 'users.id')
            ->select('orders.*', 'users.username as customer_name')
            ->orderBy('orders.created_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'revenue', 'revenueChange',
            'totalOrders', 'ordersChange',
            'newCustomers', 'customersChange',
            'activeProducts', 'totalProductsDb', 'activeProductsPercent',
            'chartLabels', 'chartData', 'range',
            'topProducts',
            'recentOrders'
        ));
    }
}
