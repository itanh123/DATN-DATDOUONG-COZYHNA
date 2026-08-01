<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Helper to check permission
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
// Backend Integrated Routes (From branch lam, adjusted)
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

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/admin/dashboard', function () {
    if (!check_permission('view_dashboard')) {
        return redirect('/login');
    }
    return view('admin.dashboard');
});

Route::get('/admin/product', function (\Illuminate\Http\Request $request) {
    if (!check_permission('view_products')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->index($request);
});

Route::post('/admin/product/store', function (\Illuminate\Http\Request $request) {
    if (!check_permission('create_products')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->store($request);
});

Route::post('/admin/product/{product}/update', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    if (!check_permission('edit_products')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->update($request, $product);
});

Route::post('/admin/product/{product}/delete', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    if (!check_permission('delete_products')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->destroy($product);
});

Route::post('/admin/product/{product}/sizes', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    if (!check_permission('edit_products')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->syncSizes($request, $product);
});

// Size Routes
Route::post('/admin/size/store', function (\Illuminate\Http\Request $request) {
    if (!check_permission('create_sizes')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->storeSize($request);
});

Route::post('/admin/size/{size}/update', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
    if (!check_permission('edit_sizes')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->updateSize($request, $size);
});

Route::post('/admin/size/{size}/delete', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
    if (!check_permission('delete_sizes')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->destroySize($size);
});

// Category Routes
Route::post('/admin/category/store', function (\Illuminate\Http\Request $request) {
    if (!check_permission('create_categories')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->storeCategory($request);
});

Route::post('/admin/category/{category}/update', function (\Illuminate\Http\Request $request, \App\Models\Category $category) {
    if (!check_permission('edit_categories')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->updateCategory($request, $category);
});

Route::post('/admin/category/{category}/delete', function (\Illuminate\Http\Request $request, \App\Models\Category $category) {
    if (!check_permission('delete_categories')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->destroyCategory($category);
});

// User Management Routes
Route::get('/admin/users', function () {
    if (!check_permission('view_users')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\UserController')->index();
});

Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
    // Only admin can create new users (or we can add create_users permission later)
    if (session('role_code') !== 'admin') {
        return redirect('/login');
    }
    return app('App\Http\Controllers\UserController')->store($request);
});

Route::post('/admin/users/{user}/role', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
    if (!check_permission('assign_roles')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\UserController')->updateRole($request, $user);
});

// Roles and Permissions Routes
Route::get('/admin/roles', function () {
    if (!check_permission('view_roles')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\RoleController')->index();
});

Route::post('/admin/roles/update', function (\Illuminate\Http\Request $request) {
    if (!check_permission('manage_permissions')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\RoleController')->updatePermissions($request);
});

// ---------------------------------------------------------
// Static UI Routes (From branch anhvh)
// ---------------------------------------------------------
Route::get('/customer/auth', function () { return view('customer.auth'); });
Route::get('/customer/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/customer/checkout/init', [\App\Http\Controllers\CartController::class, 'initCheckout'])->name('cart.initCheckout');
Route::get('/customer/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('customer.checkout');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/customer/contact', function () { return view('customer.contact'); });
Route::get('/customer/account', [\App\Http\Controllers\ProfileController::class, 'customerAccount'])->name('customer.account');
Route::post('/customer/account/update', [\App\Http\Controllers\ProfileController::class, 'updateCustomer'])->name('customer.profile.update');
Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
Route::get('/customer/orders', [\App\Http\Controllers\OrderController::class, 'history'])->name('customer.orders');
Route::post('/orders/place', [\App\Http\Controllers\OrderController::class, 'place'])->name('orders.place');
Route::post('/orders/{id}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/customer/notifications', function () { return view('customer.notifications'); });
Route::get('/customer/product_detail', function () { return view('customer.product_detail'); });

Route::get('/admin/add_product', function () { return view('admin.add_product'); });
Route::get('/admin/inventory', function () { return view('admin.inventory'); });
Route::get('/admin/orders', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
Route::get('/admin/orders/{id}', [\App\Http\Controllers\AdminOrderController::class, 'show'])->name('admin.orders.show');
Route::post('/admin/orders/{id}/status', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
Route::post('/admin/orders/{id}/assign', [\App\Http\Controllers\AdminOrderController::class, 'assignShipper'])->name('admin.orders.assign');
Route::get('/admin/products', function () { return view('admin.products'); });
Route::get('/admin/promotions', function () { return view('admin.promotions'); });
Route::get('/admin/reports', function () { return view('admin.reports'); });

Route::get('/staff/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard']);
Route::get('/staff/order_fulfillment', [\App\Http\Controllers\StaffController::class, 'dashboard']);
Route::post('/staff/orders/{id}/confirm', [\App\Http\Controllers\StaffController::class, 'confirm'])->name('staff.orders.confirm');
Route::post('/staff/orders/{id}/complete', [\App\Http\Controllers\StaffController::class, 'complete'])->name('staff.orders.complete');

// Shipper Routes
Route::get('/shipper/delivery_portal', [\App\Http\Controllers\ShipperController::class, 'portal'])->name('shipper.portal');
Route::post('/shipper/orders/{id}/accept', [\App\Http\Controllers\ShipperController::class, 'acceptOrder'])->name('shipper.orders.accept');
Route::post('/shipper/orders/{id}/status', [\App\Http\Controllers\ShipperController::class, 'updateStatus'])->name('shipper.orders.status');
Route::get('/shipper/dashboard', function () { return view('shipper.dashboard'); });
Route::get('/shipper/profile', [\App\Http\Controllers\ProfileController::class, 'shipperProfile'])->name('shipper.profile');
Route::post('/shipper/profile/update', [\App\Http\Controllers\ProfileController::class, 'updateShipper'])->name('shipper.profile.update');

