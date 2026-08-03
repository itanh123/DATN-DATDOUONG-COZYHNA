<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\ShipperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        if (!check_permission('view_orders')) {
            return redirect('/login')->with('error', 'You do not have permission to access this page.');
        }

        $query = Order::with(['customer.user', 'items.productSize.product', 'items.productSize.size', 'items.toppings.topping', 'shipper.user', 'payment']);

        if ($request->filled('status')) {
            $query->where(function($q) use ($request) {
                $q->where('order_status', strtoupper($request->status))
                  ->orWhere('status', strtolower($request->status));
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        $shippers = ShipperProfile::with('user')->where('status', 'Available')->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'orders' => $orders,
                'shippers' => $shippers
            ]);
        }

        $today = \Carbon\Carbon::today();
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $pendingPrep = Order::whereIn('order_status', ['PENDING', 'PREPARING'])->count();
        $delivering = Order::where('order_status', 'DELIVERING')->count();
        
        $completedToday = Order::whereDate('created_at', $today)
            ->where('order_status', 'COMPLETED')
            ->whereNotNull('completed_at')
            ->get();
            
        $avgFulfillment = 'N/A';
        if ($completedToday->count() > 0) {
            $totalMinutes = 0;
            foreach ($completedToday as $o) {
                $totalMinutes += $o->created_at->diffInMinutes(\Carbon\Carbon::parse($o->completed_at));
            }
            $avgMinutes = round($totalMinutes / $completedToday->count());
            $avgFulfillment = $avgMinutes . 'p';
        }

        return view('admin.orders', compact('orders', 'shippers', 'todayOrders', 'pendingPrep', 'delivering', 'avgFulfillment'));
    }

    public function show($id)
    {
        if (!check_permission('view_orders')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::with(['customer.user', 'items.productSize.product', 'items.productSize.size', 'items.toppings.topping', 'shipper.user', 'payment'])->findOrFail($id);
        
        $histories = OrderStatusHistory::with('changedBy')
            ->where('order_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'order' => $order,
            'histories' => $histories
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!check_permission('edit_orders') && !check_permission('update_orders')) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect('/login')->with('error', 'You do not have permission.');
        }

        $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::findOrFail($id);
        $newStatusStr = strtoupper($request->status);
        $newStatusLower = strtolower($request->status);

        DB::transaction(function () use ($request, $order, $newStatusStr, $newStatusLower) {
            $oldStatus = $order->order_status ?? strtoupper($order->status);
            
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatusStr,
                'changed_by' => session('user_id'),
                'note' => 'Admin cập nhật trạng thái: ' . $newStatusStr,
            ]);

            $order->order_status = $newStatusStr;
            $order->status = $newStatusLower;

            // Deduct stock if status changed to SHIPPING/DELIVERING
            if (in_array($newStatusStr, ['DELIVERING', 'SHIPPING']) && !in_array($oldStatus, ['DELIVERING', 'SHIPPING', 'COMPLETED', 'COMPLETED', 'CANCELLED'])) {
                $orderItems = DB::table('order_items')->where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    if ($item->product_size_id) {
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
            }

            if ($newStatusStr === 'COMPLETED') {
                $order->completed_at = now();
                
                if ($order->shipper_id && $oldStatus !== 'COMPLETED') {
                    $shipper = ShipperProfile::find($order->shipper_id);
                    if ($shipper) {
                        $shipper->increment('total_deliveries');
                    }
                }
                
                DB::table('payments')
                    ->where('order_id', $order->id)
                    ->where('payment_status', 'PENDING')
                    ->update(['payment_status' => 'COMPLETED']);

            } else if ($newStatusStr === 'CANCELLED') {
                $order->cancelled_at = now();
            }

            $order->save();
        });

        // Email Notification
        try {
            $customerUser = DB::table('customer_profiles')
                ->join('users', 'customer_profiles.user_id', '=', 'users.id')
                ->where('customer_profiles.id', $order->customer_id)
                ->select('users.email', 'users.username')
                ->first();

            if ($customerUser && $customerUser->email) {
                Mail::to($customerUser->email)
                    ->send(new \App\Mail\OrderStatusChanged($order, $customerUser->username, $newStatusStr));
            }
        } catch (\Exception $e) {
            Log::error('Mail Error: ' . $e->getMessage());
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        }

        return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
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
        
        $order->shipper_id = $request->shipper_id;
        if ($request->shipper_id && in_array(strtoupper($order->order_status), ['PREPARING', 'PENDING'])) {
            $order->order_status = 'DELIVERING';
            $order->status = 'shipping';
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => 'PREPARING',
                'new_status' => 'DELIVERING',
                'changed_by' => session('user_id'),
                'note' => 'Admin gán Shipper: ' . $request->shipper_id,
            ]);
        }

        $order->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Gán Shipper thành công']);
        }

        return redirect()->back()->with('success', 'Gán Shipper thành công');
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
