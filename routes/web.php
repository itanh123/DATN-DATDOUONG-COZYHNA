<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------
// Helper to check permission
// ---------------------------------------------------------
if (!function_exists('check_permission')) {
    function check_permission($permissionCode) {
        $roleCode = session('role_code');
        if ($roleCode === 'admin') return true;
        if (!$roleCode) return false;
        
        $role = \Illuminate\Support\Facades\DB::table('roles')->where('code', $roleCode)->first();
        if (!$role) return false;
        
        return \Illuminate\Support\Facades\DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', $role->id)
            ->where('permissions.code', $permissionCode)
            ->exists();
    }
}

// ---------------------------------------------------------
// Public & Customer Routes
// ---------------------------------------------------------
Route::get('/', function () {
    $products = \App\Models\Product::query()
        ->whereNull('deleted_at')
        ->where('status', true)
        ->with(['productSizes.size', 'category'])
        ->get();

    $categories = \App\Models\Category::where('status', true)->get();

    return view('customer.home', compact('products', 'categories'));
});

// Authentication
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

// Customer Pages
Route::get('/customer/auth', function () { return view('customer.auth'); });
Route::get('/customer/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('customer.checkout');
Route::post('/customer/checkout/apply-voucher', [\App\Http\Controllers\CheckoutController::class, 'applyVoucher']);
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/customer/contact', function () { return view('customer.contact'); });
Route::get('/customer/account', function () {
    $userId = session('user_id');
    if (!$userId) return redirect('/login');
    $user = \App\Models\User::with('customerProfile')->find($userId);
    if (!$user) return redirect('/login');
    return view('customer.account', compact('user'));
})->name('customer.account');
Route::get('/customer/orders', [\App\Http\Controllers\OrderController::class, 'history'])->name('customer.orders');
Route::post('/orders/place', [\App\Http\Controllers\OrderController::class, 'place'])->name('orders.place');
Route::post('/orders/{id}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/customer/notifications', function () { return view('customer.notifications'); });
Route::get('/customer/product_detail', function () { return view('customer.product_detail'); });


// ---------------------------------------------------------
// Admin Routes
// ---------------------------------------------------------
Route::prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        if (!check_permission('view_dashboard')) return redirect('/login');
        return view('admin.dashboard');
    });

    // Product Management (Integrated)
    Route::get('/product', function (\Illuminate\Http\Request $request) {
        if (!check_permission('view_products')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->index($request);
    });
    Route::post('/product/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('create_products')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->store($request);
    });
    Route::post('/product/{product}/update', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        if (!check_permission('edit_products')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->update($request, $product);
    });
    Route::post('/product/{product}/delete', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        if (!check_permission('delete_products')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->destroy($product);
    });
    Route::post('/product/{product}/sizes', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        if (!check_permission('edit_products')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->syncSizes($request, $product);
    });

    // Size Management
    Route::post('/size/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('create_sizes')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->storeSize($request);
    });
    Route::post('/size/{size}/update', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
        if (!check_permission('edit_sizes')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->updateSize($request, $size);
    });
    Route::post('/size/{size}/delete', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
        if (!check_permission('delete_sizes')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->destroySize($size);
    });

    // Category Management
    Route::post('/category/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('create_categories')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->storeCategory($request);
    });
    Route::post('/category/{category}/update', function (\Illuminate\Http\Request $request, \App\Models\Category $category) {
        if (!check_permission('edit_categories')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->updateCategory($request, $category);
    });
    Route::post('/category/{category}/delete', function (\Illuminate\Http\Request $request, \App\Models\Category $category) {
        if (!check_permission('delete_categories')) return redirect('/login');
        return app('App\Http\Controllers\ProductController')->destroyCategory($category);
    });

    // User Management
    Route::get('/users', function () {
        if (!check_permission('view_users')) return redirect('/login');
        return app('App\Http\Controllers\UserController')->index();
    });
    Route::post('/users', function (\Illuminate\Http\Request $request) {
        if (session('role_code') !== 'admin') return redirect('/login');
        return app('App\Http\Controllers\UserController')->store($request);
    });
    Route::post('/users/{user}/role', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        if (!check_permission('assign_roles')) return redirect('/login');
        return app('App\Http\Controllers\UserController')->updateRole($request, $user);
    });

    // Roles and Permissions
    Route::get('/roles', function () {
        if (!check_permission('view_roles')) return redirect('/login');
        return app('App\Http\Controllers\RoleController')->index();
    });
    Route::post('/roles/update', function (\Illuminate\Http\Request $request) {
        if (!check_permission('manage_permissions')) return redirect('/login');
        return app('App\Http\Controllers\RoleController')->updatePermissions($request);
    });

    // Vouchers (Using Admin Middleware)
    Route::get('/voucher', [\App\Http\Controllers\VoucherController::class, 'index']);
    Route::get('/voucher/add', [\App\Http\Controllers\VoucherController::class, 'create']);
    Route::post('/voucher/store', [\App\Http\Controllers\VoucherController::class, 'store']);
    Route::get('/voucher/{voucher}/edit', [\App\Http\Controllers\VoucherController::class, 'edit']);
    Route::post('/voucher/{voucher}/update', [\App\Http\Controllers\VoucherController::class, 'update']);
    Route::post('/voucher/{voucher}/delete', [\App\Http\Controllers\VoucherController::class, 'destroy']);

    // Static UI Routes (Not yet integrated with backend logic)
    Route::get('/add_product', function () { return view('admin.add_product'); });
    Route::get('/customers', function () { return view('admin.customers'); });
    Route::get('/employees', function () { return view('admin.employees'); });
    Route::get('/inventory', function () { return view('admin.inventory'); });
    Route::get('/orders', function () { return view('admin.orders'); });
    Route::get('/products', function () { return view('admin.products'); }); // Static view, may be redundant with /product
    Route::get('/promotions', function () { return view('admin.promotions'); });
    Route::get('/reports', function () { return view('admin.reports'); });
});

// ---------------------------------------------------------
// Staff Routes
// ---------------------------------------------------------
Route::prefix('staff')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard']);
    Route::get('/order_fulfillment', [\App\Http\Controllers\StaffController::class, 'dashboard']);
    Route::post('/orders/{id}/confirm', [\App\Http\Controllers\StaffController::class, 'confirm'])->name('staff.orders.confirm');
    Route::post('/orders/{id}/complete', [\App\Http\Controllers\StaffController::class, 'complete'])->name('staff.orders.complete');
});

// ---------------------------------------------------------
// Shipper Routes
// ---------------------------------------------------------
Route::prefix('shipper')->group(function () {
    Route::get('/delivery_portal', function () { return view('shipper.delivery_portal'); });
    Route::get('/dashboard', function () { return view('shipper.dashboard'); });
    Route::get('/profile', function () { return view('shipper.profile'); });
});
