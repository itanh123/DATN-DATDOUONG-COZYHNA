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
            ->leftJoin('dining_tables', 'users.id', '=', 'dining_tables.user_id')
            ->select(
                'orders.*', 
                'users.username as customer_name',
                'customer_addresses.receiver_name',
                'customer_addresses.receiver_phone',
                'customer_addresses.address as shipping_address',
                'dining_tables.name as table_name'
            );

        // Filter by Tab Type (Online vs At Table)
        $activeTab = $request->input('type', 'online');
        $query->where('orders.order_type', $activeTab);

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

        $latestOrderId = DB::table('orders')->max('id');

        return view('admin.orders', compact('orders', 'totalOrdersToday', 'pendingPrep', 'outForDelivery', 'avgFulfillment', 'statusFilter', 'dateFilter', 'latestOrderId', 'activeTab'));
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

            // Create PDF invoice if status is 'preparing'
            if ($status === 'preparing') {
                try {
                    $customerData = DB::table('customer_profiles')
                        ->join('users', 'customer_profiles.user_id', '=', 'users.id')
                        ->where('customer_profiles.id', $order->customer_id)
                        ->select('users.name', 'users.username', 'users.email', 'users.phone')
                        ->first();
                        
                    $addressData = DB::table('customer_addresses')->where('id', $order->address_id)->first();
                    
                    $itemsData = DB::table('order_items')
                        ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                        ->join('products', 'product_sizes.product_id', '=', 'products.id')
                        ->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
                        ->where('order_items.order_id', $order->id)
                        ->select('order_items.*', 'products.name as product_name', 'sizes.name as size_name')
                        ->get();

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', [
                        'order' => $order,
                        'customer' => $customerData,
                        'address' => $addressData,
                        'items' => $itemsData
                    ]);
                    
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('invoices')) {
                        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('invoices');
                    }
                    
                    \Illuminate\Support\Facades\Storage::disk('public')->put('invoices/' . $order->order_code . '.pdf', $pdf->output());
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('PDF Generation Error: ' . $e->getMessage());
                }
            }

            // Send Email Notification
            try {
                $customer = DB::table('customer_profiles')
                    ->join('users', 'customer_profiles.user_id', '=', 'users.id')
                    ->where('customer_profiles.id', $order->customer_id)
                    ->select('users.email', 'users.username')
                    ->first();

                if ($customer && $customer->email) {
                    $statusMessages = [
                        'pending' => 'Đang chờ xác nhận',
                        'confirmed' => 'Đã được xác nhận',
                        'preparing' => 'Đang được chuẩn bị',
                        'shipping' => 'Đang được giao đến bạn',
                        'completed' => 'Đã giao thành công',
                        'cancelled' => 'Đã bị hủy'
                    ];
                    $msg = $statusMessages[$status] ?? $status;
                    \Illuminate\Support\Facades\Mail::to($customer->email)
                        ->send(new \App\Mail\OrderStatusChanged($order, $customer->username, $msg));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
        }
        
        return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
    }

    public function checkNew(Request $request)
    {
        $latestId = $request->input('latest_id', 0);
        $newOrdersCount = DB::table('orders')->where('id', '>', $latestId)->count();

        return response()->json([
            'has_new' => $newOrdersCount > 0,
            'count' => $newOrdersCount
        ]);
    }
}
