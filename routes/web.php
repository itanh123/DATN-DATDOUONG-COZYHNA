<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = \App\Models\Product::query()
        ->whereNull('deleted_at')
        ->where('status', true)
        ->get();

    return view('client.home', compact('products'));
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

    return app('App\\Http\\Controllers\\ProductController')->index();
});

Route::post('/admin/product/store', function (\Illuminate\Http\Request $request) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\\Http\\Controllers\\ProductController')->store($request);
});

Route::post('/admin/product/{product}/update', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\\Http\\Controllers\\ProductController')->update($request, $product);
});

Route::post('/admin/product/{product}/delete', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    $role = session('role_code');
    if ($role !== 'admin') {
        return redirect('/login');
    }

    return app('App\\Http\\Controllers\\ProductController')->destroy($product);
});





