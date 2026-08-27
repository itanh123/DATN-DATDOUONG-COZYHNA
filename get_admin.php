<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::whereHas('role', function($q) {
    $q->where('code', 'admin');
})->first();

if ($user) {
    echo $user->email;
} else {
    echo "No admin found";
}
