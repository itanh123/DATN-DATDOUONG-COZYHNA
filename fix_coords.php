<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = App\Models\Order::whereNull('delivery_latitude')->whereNotNull('delivery_address')->get();
foreach($orders as $order) {
    // Just force arbitrary coords for demo purposes for these broken orders
    $order->delivery_latitude = '20.2506'; 
    $order->delivery_longitude = '105.9745';
    $order->save();
    echo "Fixed order {$order->id}\n";
}
echo "Done.";
