<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('order_item_toppings')->truncate();
        DB::table('order_items')->truncate();
        DB::table('payments')->truncate();
        DB::table('orders')->truncate();
        DB::table('product_reviews')->truncate();
        Schema::enableForeignKeyConstraints();

        $customerProfile = DB::table('customer_profiles')->first();
        $customerAddress = DB::table('customer_addresses')->first();
        $shipperProfile = DB::table('shipper_profiles')->first();
        $productSizes = DB::table('product_sizes')->get();
        $toppings = DB::table('toppings')->get();
        $user = DB::table('users')->where('email', 'khachhang@gmail.com')->first();

        if (!$customerProfile || $productSizes->isEmpty()) {
            return;
        }

        $statuses = ['completed', 'shipping', 'preparing', 'confirmed', 'pending'];
        $paymentMethods = ['cash', 'vnpay', 'momo', 'bank'];

        foreach ($statuses as $index => $status) {
            $orderCode = 'ORD-' . strtoupper(Str::random(8));
            $productSize = $productSizes[$index % count($productSizes)];
            $unitPrice = $productSize->selling_price ?? 30000;
            $qty = rand(1, 3);
            $subtotal = $unitPrice * $qty;
            $shippingFee = 15000;
            $discount = 10000;
            $total = $subtotal + $shippingFee - $discount;

            $orderId = DB::table('orders')->insertGetId([
                'order_code' => $orderCode,
                'customer_id' => $customerProfile->id,
                'address_id' => $customerAddress?->id,
                'shipper_id' => ($status === 'shipping' || $status === 'completed') ? $shipperProfile?->id : null,
                'voucher_id' => null,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'total_amount' => $total,
                'payment_method' => $paymentMethods[$index % count($paymentMethods)],
                'status' => $status,
                'note' => 'Ít đường, nhiều đá giúp mình nhé!',
                'ordered_at' => now()->subHours(($index + 1) * 3),
                'created_at' => now()->subHours(($index + 1) * 3),
                'updated_at' => now(),
            ]);

            $orderItemId = DB::table('order_items')->insertGetId([
                'order_id' => $orderId,
                'product_size_id' => $productSize->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $subtotal,
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add topping for item if available
            if ($toppings->isNotEmpty() && Schema::hasTable('order_item_toppings')) {
                $topping = $toppings[0];
                DB::table('order_item_toppings')->insert([
                    'order_item_id' => $orderItemId,
                    'topping_id' => $topping->id,
                    'quantity' => 1,
                    'unit_price' => $topping->price,
                    'total_price' => $topping->price,
                ]);
            }

            // Add payment record
            DB::table('payments')->insert([
                'order_id' => $orderId,
                'transaction_code' => 'TXN-' . rand(10000000, 99999999),
                'amount' => $total,
                'method' => $paymentMethods[$index % count($paymentMethods)],
                'status' => ($status === 'completed' || $status === 'shipping') ? 'paid' : 'pending',
                'paid_at' => ($status === 'completed' || $status === 'shipping') ? now()->subHours(($index + 1) * 3) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add product review for completed order
            if ($status === 'completed' && $user) {
                DB::table('product_reviews')->insert([
                    'user_id' => $user->id,
                    'product_id' => $productSize->product_id,
                    'order_id' => $orderId,
                    'rating' => 5,
                    'comment' => 'Nước uống rất ngon, trân châu dẻo thơm, giao hàng nhanh tuyệt vời!',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
