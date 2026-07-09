<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.home');
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


