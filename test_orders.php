<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$today = \Carbon\Carbon::today();
$orders = \App\Models\Order::whereDate('created_at', $today)
    ->select('id', 'order_code', 'order_status', 'order_type', 'shipper_id')
    ->get();

echo "Total orders today: " . $orders->count() . "\n";
echo "Details:\n";
foreach($orders as $o) {
    echo "ID: {$o->id} | Code: {$o->order_code} | Status: {$o->order_status} | Type: {$o->order_type} | Shipper: {$o->shipper_id}\n";
}
