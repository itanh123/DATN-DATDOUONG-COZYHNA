<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$shippers = \App\Models\ShipperProfile::all();
foreach ($shippers as $shipper) {
    $avgRating = \App\Models\Order::where('shipper_id', $shipper->id)
        ->whereNotNull('shipper_rating')
        ->avg('shipper_rating');
        
    if ($avgRating !== null) {
        $shipper->rating = round($avgRating, 1);
        $shipper->save();
        echo "Updated Shipper {$shipper->id} to {$shipper->rating}\n";
    }
}
echo "Done syncing all shippers.\n";
