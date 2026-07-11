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
        ->with(['productSizes.size', 'category'])
        ->get();

    return view('customer.home', compact('products'));
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/admin/dashboard', function () {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return view('admin.dashboard');
});

Route::get('/admin/product', function () {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->index();
});

Route::post('/admin/product/store', function (\Illuminate\Http\Request $request) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->store($request);
});

Route::post('/admin/product/{product}/update', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->update($request, $product);
});

Route::post('/admin/product/{product}/delete', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->destroy($product);
});

Route::post('/admin/product/{product}/sizes', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->syncSizes($request, $product);
});

// Size Routes
Route::post('/admin/size/store', function (\Illuminate\Http\Request $request) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->storeSize($request);
});

Route::post('/admin/size/{size}/update', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->updateSize($request, $size);
});

Route::post('/admin/size/{size}/delete', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\Http\Controllers\ProductController')->destroySize($size);
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

Route::get('/admin/add_product', function () { return view('admin.add_product'); });
Route::get('/admin/customers', function () { return view('admin.customers'); });
Route::get('/admin/employees', function () { return view('admin.employees'); });
Route::get('/admin/inventory', function () { return view('admin.inventory'); });
Route::get('/admin/orders', function () { return view('admin.orders'); });
Route::get('/admin/products', function () { return view('admin.products'); });
Route::get('/admin/promotions', function () { return view('admin.promotions'); });
Route::get('/admin/reports', function () { return view('admin.reports'); });

Route::get('/staff/dashboard', function () { return view('staff.dashboard'); });
Route::get('/staff/order_fulfillment', function () { return view('staff.order_fulfillment'); });

Route::get('/shipper/delivery_portal', function () { return view('shipper.delivery_portal'); });
Route::get('/shipper/dashboard', function () { return view('shipper.dashboard'); });
Route::get('/shipper/profile', function () { return view('shipper.profile'); });
