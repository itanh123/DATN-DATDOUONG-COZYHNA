<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$recipes = \App\Models\Recipe::with('ingredients')->get();
foreach ($recipes as $r) {
    echo "Recipe ID: {$r->id}, Size ID: {$r->product_size_id}, Active: {$r->is_active}, Ingredients count: " . count($r->ingredients) . "\n";
}
