<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$shipper = \App\Models\ShipperProfile::whereHas('user', function($q) {
    $q->where('email', 'hoang@gmail.com');
})->first();

if ($shipper) {
    echo "Shipper found: ID {$shipper->id}\n";
    echo "Current rating in DB: {$shipper->rating}\n";
    
    $avgRating = \App\Models\Order::where('shipper_id', $shipper->id)
        ->whereNotNull('shipper_rating')
        ->avg('shipper_rating');
        
    echo "Calculated average rating from orders: " . ($avgRating ?? 'NULL') . "\n";
    
    // Attempt to update
    if ($avgRating !== null) {
        $shipper->rating = round($avgRating, 1);
        $saved = $shipper->save();
        echo "Saved to DB: " . ($saved ? 'YES' : 'NO') . " | New rating in DB: {$shipper->rating}\n";
    }
} else {
    echo "Shipper not found\n";
}
