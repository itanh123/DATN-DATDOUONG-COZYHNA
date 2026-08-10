<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = \App\Models\User::where('email', 'admin@gmail.com')->first();
$u->password = \Illuminate\Support\Facades\Hash::make('password123');
$u->save();
echo "Admin password reset to password123";
