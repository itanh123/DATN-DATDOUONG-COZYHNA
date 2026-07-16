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
        ->with(['productSizes.size', 'productSizes.recipes.ingredients.ingredient', 'category'])
        ->get();

    $availableProducts = $products->filter(function ($product) {
        $hasAvailableSize = false;
        foreach ($product->productSizes as $size) {
            $isSizeAvailable = true;
            $recipe = $size->recipes->first();
            if ($recipe) {
                foreach ($recipe->ingredients as $ri) {
                    if ($ri->ingredient && $ri->ingredient->current_stock < $ri->quantity) {
                        $isSizeAvailable = false;
                        break;
                    }
                }
            }
            if ($isSizeAvailable) {
                $hasAvailableSize = true;
                break;
            }
        }
        return $hasAvailableSize;
    });

    $products = $availableProducts;

    return view('customer.home', compact('products'));
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/auth/google', [\App\Http\Controllers\AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [\App\Http\Controllers\AuthController::class, 'handleGoogleCallback']);

Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index']);

Route::get('/admin/ingredients', [\App\Http\Controllers\AdminIngredientController::class, 'index']);
Route::post('/admin/ingredients', [\App\Http\Controllers\AdminIngredientController::class, 'store']);
Route::put('/admin/ingredients/{id}', [\App\Http\Controllers\AdminIngredientController::class, 'update']);
Route::delete('/admin/ingredients/{id}', [\App\Http\Controllers\AdminIngredientController::class, 'destroy']);

Route::get('/admin/product', function (\Illuminate\Http\Request $request) {
    if (!check_permission('view_products')) {
        return redirect('/login');
    }
    return app('App\Http\Controllers\ProductController')->index($request);
});
Route::get('/admin/product/{product}/recipe', [\App\Http\Controllers\ProductController::class, 'recipe']);
Route::post('/admin/product/{product}/recipe', [\App\Http\Controllers\ProductController::class, 'updateRecipe']);

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

Route::post('/admin/users/{user}/password', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
    // We check permission inside the controller based on user_id
    return app('App\Http\Controllers\UserController')->updatePassword($request, $user);
});

Route::post('/admin/users/{user}/toggle-status', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
    return app('App\Http\Controllers\UserController')->toggleStatus($request, $user);
});

Route::post('/admin/users/{user}/toggle-restriction', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
    return app('App\Http\Controllers\UserController')->toggleRestriction($request, $user);
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
Route::get('/customer/checkout', [\App\Http\Controllers\CheckoutController::class, 'index']);
Route::post('/customer/checkout/apply-voucher', [\App\Http\Controllers\CheckoutController::class, 'applyVoucher']);
Route::post('/customer/checkout/place-order', [\App\Http\Controllers\CheckoutController::class, 'placeOrder']);
Route::post('/cart/add', [\App\Http\Controllers\CheckoutController::class, 'addToCart']);
Route::post('/cart/update-quantity', [\App\Http\Controllers\CheckoutController::class, 'updateQuantity']);
Route::get('/customer/contact', function () { return view('customer.contact'); });
Route::get('/customer/account', function () {
    if (!session()->has('user_id')) {
        return redirect('/login');
    }
    $user = \App\Models\User::find(session('user_id'));
    return view('customer.account', compact('user'));
});
Route::post('/customer/account/update', [\App\Http\Controllers\AuthController::class, 'updateProfile']);
Route::get('/customer/orders', [\App\Http\Controllers\OrderController::class, 'customerOrders']);
Route::get('/customer/notifications', function () { return view('customer.notifications'); });
Route::get('/customer/product_detail', function () { return view('customer.product_detail'); });

Route::get('/admin/add_product', function () { return view('admin.add_product'); });
Route::get('/admin/inventory', function () { return view('admin.inventory'); });
Route::get('/admin/orders', [\App\Http\Controllers\AdminOrderController::class, 'index']);
Route::post('/admin/orders/{id}/status', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus']);
Route::get('/admin/products', function () { return view('admin.products'); });
Route::get('/admin/promotions', function () { return view('admin.promotions'); });
Route::get('/admin/reports', function () { return view('admin.reports'); });

// Voucher Routes
Route::get('/admin/voucher', [\App\Http\Controllers\VoucherController::class, 'index']);
Route::get('/admin/voucher/add', [\App\Http\Controllers\VoucherController::class, 'create']);
Route::post('/admin/voucher/store', [\App\Http\Controllers\VoucherController::class, 'store']);
Route::get('/admin/voucher/{voucher}/edit', [\App\Http\Controllers\VoucherController::class, 'edit']);
Route::post('/admin/voucher/{voucher}/update', [\App\Http\Controllers\VoucherController::class, 'update']);
Route::post('/admin/voucher/{voucher}/delete', [\App\Http\Controllers\VoucherController::class, 'destroy']);

Route::get('/staff/dashboard', function () { return view('staff.dashboard'); });
Route::get('/staff/order_fulfillment', function () { return view('staff.order_fulfillment'); });

Route::get('/shipper/delivery_portal', function () { return view('shipper.delivery_portal'); });
Route::get('/shipper/dashboard', function () { return view('shipper.dashboard'); });
Route::get('/shipper/profile', function () { return view('shipper.profile'); });