<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::whereNotNull('shipper_id')->where('order_status', 'COMPLETED')->first();
if ($order) {
    echo "Found Order: " . $order->id . " with Shipper: " . $order->shipper_id . "\n";
    $order->update(['shipper_rating' => 4]);
    
    // Simulate OrderController logic
    $shipper = \App\Models\ShipperProfile::find($order->shipper_id);
    $avgRating = \App\Models\Order::where('shipper_id', $shipper->id)
                        ->whereNotNull('shipper_rating')
                        ->avg('shipper_rating');
    echo "Avg Rating calculated: $avgRating\n";
    $shipper->rating = round($avgRating, 1);
    $shipper->save();
    
    echo "Shipper {$shipper->id} new rating is: {$shipper->rating}\n";
} else {
    echo "No completed orders with a shipper found.\n";
}
