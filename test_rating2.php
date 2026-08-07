<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$shippers = \App\Models\ShipperProfile::with('user')->get();
foreach($shippers as $s) {
    echo "ID: {$s->id} | User: " . ($s->user->email ?? 'N/A') . " | Rating: {$s->rating}\n";
}
