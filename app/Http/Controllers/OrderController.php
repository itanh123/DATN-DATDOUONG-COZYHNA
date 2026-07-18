<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function customerOrders()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập.');
        }

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) {
            return redirect('/');
        }

        $orders = DB::table('orders')
            ->where('customer_id', $customerProfile->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewedItems = DB::table('product_reviews')
            ->where('user_id', $userId)
            ->select('order_id', 'product_id')
            ->get()
            ->map(function ($review) {
                return $review->order_id . '_' . $review->product_id;
            })->toArray();

        foreach ($orders as $order) {
            $order->items = DB::table('order_items')
                ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                ->join('products', 'product_sizes.product_id', '=', 'products.id')
                ->join('sizes', 'product_sizes.size_id', '=', 'sizes.id')
                ->where('order_items.order_id', $order->id)
                ->select('order_items.*', 'products.name as product_name', 'products.image as product_image', 'sizes.name as size_name', 'products.id as product_id')
                ->get();

            foreach ($order->items as $item) {
                $item->is_reviewed = in_array($order->id . '_' . $item->product_id, $reviewedItems);
            }

            $order->address = DB::table('customer_addresses')
                ->where('id', $order->address_id)
                ->first();
                
            $order->shipper = null;
            if ($order->shipper_id) {
                $order->shipper = DB::table('shipper_profiles')
                    ->join('users', 'shipper_profiles.user_id', '=', 'users.id')
                    ->where('shipper_profiles.id', $order->shipper_id)
                    ->select('users.name as name')
                    ->first();
            }
        }

        $activeOrders = $orders->filter(function ($order) {
            return !in_array($order->status, ['completed', 'cancelled']);
        });

        $historyOrders = $orders->filter(function ($order) {
            return in_array($order->status, ['completed', 'cancelled']);
        });

        return view('customer.orders', compact('activeOrders', 'historyOrders'));
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $userId = session('user_id');
        if (!$userId) return redirect('/login');

        $customerProfile = DB::table('customer_profiles')->where('user_id', $userId)->first();
        if (!$customerProfile) return back()->with('error', 'Không tìm thấy thông tin khách hàng');

        $order = DB::table('orders')
            ->where('id', $orderId)
            ->where('customer_id', $customerProfile->id)
            ->first();

        if (!$order) {
            return back()->with('error', 'Đơn hàng không tồn tại hoặc bạn không có quyền hủy.');
        }

        if (in_array($order->status, ['preparing', 'shipping', 'delivering', 'completed', 'cancelled'])) {
            return back()->with('error', 'Đơn hàng đang chuẩn bị hoặc đã giao, không thể hủy.');
        }

        $cancelReason = request('cancel_reason') ?: 'Không có lý do';

        DB::table('orders')->where('id', $order->id)->update([
            'status' => 'cancelled',
            'cancel_reason' => $cancelReason,
            'updated_at' => now()
        ]);

        try {
            $user = DB::table('users')->where('id', $userId)->first();
            if ($user && $user->email) {
                \Illuminate\Support\Facades\Mail::to($user->email)
                    ->send(new \App\Mail\OrderStatusChanged($order, $user->username, 'Đã bị hủy bởi khách hàng'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Error: ' . $e->getMessage());
        }

        return back()->with('cancel_success', 'Đã hủy đơn hàng! Cảm ơn bạn đã góp ý kiến.');
    }
}
