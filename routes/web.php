<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------
// Backend Integrated Routes (From branch lam, adjusted)
// ---------------------------------------------------------

Route::get('/', function () {
    $products = \App\Models\Product::query()
        ->whereNull('deleted_at')
        ->where('status', true)
        ->get();

    return view('customer.home', compact('products'));
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/product', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/store', [\App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/{product}/update', [\App\Http\Controllers\ProductController::class, 'update']);
    Route::post('/product/{product}/delete', [\App\Http\Controllers\ProductController::class, 'destroy']);

});

// ---------------------------------------------------------
// Static UI Routes (From branch anhvh)
// ---------------------------------------------------------
Route::get('/customer/auth', function () { return view('customer.auth'); });
Route::get('/customer/checkout', function () { return view('customer.checkout'); });
Route::get('/customer/contact', function () { return view('customer.contact'); });
Route::get('/customer/account', function () { return view('customer.account'); });
Route::get('/customer/orders', function () { return view('customer.orders'); });
Route::get('/customer/notifications', function () { return view('customer.notifications'); });
Route::get('/customer/product_detail', function () { return view('customer.product_detail'); });

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/add_product', function () { return view('admin.add_product'); });
    Route::get('/customers', function () { return view('admin.customers'); });
    Route::get('/employees', function () { return view('admin.employees'); });
    Route::get('/inventory', function () { return view('admin.inventory'); });
    Route::get('/orders', function () { return view('admin.orders'); });
    Route::get('/products', function () { return view('admin.products'); });
    Route::get('/promotions', function () { return view('admin.promotions'); });
    Route::get('/reports', function () { return view('admin.reports'); });
});

Route::get('/staff/dashboard', function () { return view('staff.dashboard'); });
Route::get('/staff/order_fulfillment', function () { return view('staff.order_fulfillment'); });

Route::get('/shipper/delivery_portal', function () { return view('shipper.delivery_portal'); });
Route::get('/shipper/dashboard', function () { return view('shipper.dashboard'); });
Route::get('/shipper/profile', function () { return view('shipper.profile'); });
