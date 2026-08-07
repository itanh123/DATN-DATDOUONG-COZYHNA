<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::where('order_status', 'COMPLETED')->orderByDesc('id')->first();
if ($order) {
    echo "ID: {$order->id}\n";
    echo "status: '{$order->status}'\n";
    echo "order_status: '{$order->order_status}'\n";
    
    $statusCheck = strtolower($order->status ?? $order->order_status ?? '');
    echo "Result of strtolower(\$order->status ?? \$order->order_status ?? ''): '{$statusCheck}'\n";
}
