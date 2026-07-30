<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Create PDF invoice
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
            \Illuminate\Support\Facades\Log::error('PDF Generation Error from Staff: ' . $e->getMessage());
        }

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
