<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check all COMPLETED orders with their shipping_fee
$orders = \App\Models\Order::select('id','order_code','shipping_fee','distance_km','order_status','created_at')
    ->where('order_status', 'COMPLETED')
    ->orderByDesc('id')
    ->get();

foreach ($orders as $o) {
    echo "id={$o->id} code={$o->order_code} shipping_fee={$o->shipping_fee} distance_km={$o->distance_km}\n";
}
