<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = \App\Models\User::where('username', 'staff01')->first();
if ($u) {
    $u->password = \Illuminate\Support\Facades\Hash::make('password123');
    $u->save();
    echo $u->email;
} else {
    echo 'Not found';
}
