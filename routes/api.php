<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

// --- PUBLIC APIs ---
// 1. Xác thực (Auth)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);

// 2. Sản phẩm & Danh mục
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'getByProduct']);
Route::get('/categories', [ProductController::class, 'getCategories']);
Route::get('/toppings', [ProductController::class, 'getToppings']);

// --- PROTECTED APIs (Yêu cầu đăng nhập) ---
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Order
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Review
    Route::post('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);

    // AI Chat
    Route::post('/ai/chat', [\App\Http\Controllers\AiChatController::class, 'chat']);
    Route::get('/ai/history/{sessionId}', [\App\Http\Controllers\AiChatController::class, 'history']);
});
