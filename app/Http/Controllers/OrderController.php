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

        foreach ($orders as $order) {
            $order->items = DB::table('order_items')
                ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
                ->join('products', 'product_sizes.product_id', '=', 'products.id')
                ->join('sizes', 'product_sizes.size_id', '=', 'sizes.id')
                ->where('order_items.order_id', $order->id)
                ->select('order_items.*', 'products.name as product_name', 'products.image as product_image', 'sizes.name as size_name')
                ->get();

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
}
