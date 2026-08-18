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
// Home & Auth Routes
// ---------------------------------------------------------

Route::get('/', function (\Illuminate\Http\Request $request) {
    $categories = \App\Models\Category::all();

    $query = \App\Models\Product::query()
        ->whereNull('deleted_at')
        ->where('status', true)
        ->with(['productSizes.size', 'productSizes.recipes.ingredients.ingredient', 'category', 'reviews.user']);

    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $products = $query->get();

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

    // Calculate sales
    $sales = \Illuminate\Support\Facades\DB::table('order_items')
        ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
        ->select('product_sizes.product_id', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sales'))
        ->groupBy('product_sizes.product_id')
        ->pluck('total_sales', 'product_id');

    // Sort descending by sales
    $products = $availableProducts->sortByDesc(function ($product) use ($sales) {
        return $sales->get($product->id, 0);
    });

    $isFiltered = $request->has('category_id');
    $toppings = \App\Models\Topping::where('status', true)->get();

    return view('customer.home', compact('products', 'categories', 'isFiltered', 'toppings'));
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('/forgot-password', [\App\Http\Controllers\AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password/send-otp', [\App\Http\Controllers\AuthController::class, 'sendOtp'])->name('password.email');
Route::post('/forgot-password/reset', [\App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/login/admin', [\App\Http\Controllers\AuthController::class, 'showLoginAdmin'])->name('admin.login');
Route::post('/login/admin', [\App\Http\Controllers\AuthController::class, 'loginAdmin']);

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/register/verify-otp', [\App\Http\Controllers\AuthController::class, 'verifyRegistrationOtp'])->name('register.verify');
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

Route::get('/auth/google', [\App\Http\Controllers\AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [\App\Http\Controllers\AuthController::class, 'handleGoogleCallback']);

// ---------------------------------------------------------
// Customer Cart & Order Routes
// ---------------------------------------------------------
Route::get('/customer/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/customer/checkout/init', [\App\Http\Controllers\CartController::class, 'initCheckout'])->name('cart.initCheckout');
Route::get('/customer/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('customer.checkout');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/update-variant/{id}', [\App\Http\Controllers\CartController::class, 'updateVariant'])->name('cart.updateVariant');
Route::post('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::post('/customer/vouchers/apply', [\App\Http\Controllers\CartController::class, 'applyVoucher'])->name('vouchers.apply');
Route::post('/customer/vouchers/remove', [\App\Http\Controllers\CartController::class, 'removeVoucher'])->name('vouchers.remove');
Route::post('/orders/place', [\App\Http\Controllers\OrderController::class, 'placeOrder'])->name('orders.place');
Route::get('/customer/orders', [\App\Http\Controllers\OrderController::class, 'customerOrders'])->name('customer.orders');
Route::get('/customer/orders/{order}/review', [\App\Http\Controllers\OrderController::class, 'showReviewForm'])->name('orders.review');
Route::post('/customer/orders/{order}/review', [\App\Http\Controllers\OrderController::class, 'submitReview'])->name('orders.submitReview');
Route::post('/customer/orders/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancelOrder'])->name('orders.cancel');
Route::post('/customer/orders/{order}/complaint', [\App\Http\Controllers\OrderComplaintController::class, 'store'])->name('orders.complain');

// AI Chat Assistant Routes
Route::post('/ai/chat', [\App\Http\Controllers\AiChatController::class, 'chat'])->name('ai.chat');
Route::get('/ai/history/{sessionId}', [\App\Http\Controllers\AiChatController::class, 'history'])->name('ai.history');
Route::post('/ai/feedback', [\App\Http\Controllers\AiChatController::class, 'feedback'])->name('ai.feedback');

// VietQR Payment Routes
Route::get('/payment/qr/{orderCode}', [\App\Http\Controllers\PaymentController::class, 'getVietQr'])->name('payment.qr');
Route::post('/payment/confirm/{orderCode}', [\App\Http\Controllers\PaymentController::class, 'confirmPayment'])->name('payment.confirm');

// Fake Online Payment Gateway (For Momo)
Route::get('/payment/fake-gateway/{orderCode}', [\App\Http\Controllers\PaymentController::class, 'fakeGateway'])->name('payment.fake.gateway');
Route::post('/payment/fake-gateway/process/{orderCode}', [\App\Http\Controllers\PaymentController::class, 'processFakePayment'])->name('payment.fake.process');

// VNPAY Payment Routes
Route::get('/payment/vnpay-return', [\App\Http\Controllers\PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');

Route::get('/customer/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('customer.favorites');
Route::post('/favorites/toggle/{product}', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');

Route::get('/customer/account', function () {
    if (!session()->has('user_id')) return redirect('/login');
    if (session('is_table_order')) return redirect('/')->with('error', 'Tài khoản bàn không được truy cập chức năng này.');
    return app('App\Http\Controllers\ProfileController')->customerAccount();
})->name('customer.account');

Route::post('/customer/account/update', function (\Illuminate\Http\Request $request) {
    if (session('is_table_order')) return redirect('/')->with('error', 'Tài khoản bàn không được truy cập chức năng này.');
    return app('App\Http\Controllers\ProfileController')->updateCustomer($request);
})->name('customer.profile.update');

Route::post('/profile/password/update', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
Route::post('/profile/password/verify-otp', [\App\Http\Controllers\ProfileController::class, 'verifyPasswordOtp'])->name('profile.password.verify');
Route::post('/customer/address/store', [\App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('customer.address.store');
Route::delete('/customer/address/{id}', [\App\Http\Controllers\ProfileController::class, 'deleteAddress'])->name('customer.address.delete');
Route::post('/customer/address/{id}/delete', [\App\Http\Controllers\ProfileController::class, 'deleteAddress'])->name('customer.address.delete.post');

Route::get('/customer/contact', function () { return view('customer.contact'); });
Route::get('/customer/notifications', function () { return view('customer.notifications'); });
Route::get('/customer/product_detail', function () { return view('customer.product_detail'); });
Route::post('/customer/reviews', [\App\Http\Controllers\ReviewController::class, 'store']);

Route::get('/orders/invoice/{orderCode}', [\App\Http\Controllers\AdminOrderController::class, 'printInvoice'])->name('orders.invoice');

// ---------------------------------------------------------
// Table Ordering Routes
// ---------------------------------------------------------
Route::get('/table/login/{token}', [\App\Http\Controllers\TableOrderController::class, 'loginWithQr']);
Route::match(['get', 'post'], '/table/order/confirm', [\App\Http\Controllers\TableOrderController::class, 'confirmOrder']);
Route::get('/table/order/success', [\App\Http\Controllers\TableOrderController::class, 'success']);
Route::post('/table/call-staff', [\App\Http\Controllers\TableOrderController::class, 'callStaff']);

// ---------------------------------------------------------
// Staff Routes
// ---------------------------------------------------------
Route::get('/staff/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard']);
Route::get('/staff/order_fulfillment', [\App\Http\Controllers\StaffController::class, 'dashboard']);
Route::post('/staff/orders/{id}/confirm', [\App\Http\Controllers\StaffController::class, 'confirm'])->name('staff.orders.confirm');
Route::post('/staff/orders/{id}/complete', [\App\Http\Controllers\StaffController::class, 'complete'])->name('staff.orders.complete');

// ---------------------------------------------------------
// Shipper Routes
// ---------------------------------------------------------
Route::get('/shipper/delivery_portal', [\App\Http\Controllers\ShipperController::class, 'portal'])->name('shipper.portal');
Route::get('/shipper/orders/available-html', [\App\Http\Controllers\ShipperController::class, 'availableOrdersHtml'])->name('shipper.orders.available_html');
Route::post('/shipper/orders/{id}/accept', [\App\Http\Controllers\ShipperController::class, 'acceptOrder'])->name('shipper.orders.accept');
Route::post('/shipper/orders/{id}/status', [\App\Http\Controllers\ShipperController::class, 'updateStatus'])->name('shipper.orders.status');
Route::get('/shipper/dashboard', function () { return view('shipper.dashboard'); });
Route::get('/shipper/history', [\App\Http\Controllers\ShipperController::class, 'history'])->name('shipper.history');
Route::get('/shipper/reviews', [\App\Http\Controllers\ShipperController::class, 'reviews'])->name('shipper.reviews');
Route::get('/shipper/profile', [\App\Http\Controllers\ProfileController::class, 'shipperProfile'])->name('shipper.profile');
Route::post('/shipper/profile/update', [\App\Http\Controllers\ProfileController::class, 'updateShipper'])->name('shipper.profile.update');

// ---------------------------------------------------------
// Admin Routes (Protected by middleware 'admin')
// ---------------------------------------------------------
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index']);

    // Admin Orders
    Route::get('/admin/orders', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/check-new', [\App\Http\Controllers\AdminOrderController::class, 'checkNew']);
    Route::get('/admin/orders/{id}', [\App\Http\Controllers\AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{id}/status', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::post('/admin/orders/{id}/assign', [\App\Http\Controllers\AdminOrderController::class, 'assignShipper'])->name('admin.orders.assign');

    // Admin Complaints
    Route::get('/admin/complaints/check-new', [\App\Http\Controllers\OrderComplaintController::class, 'checkNew'])->name('admin.complaints.check');
    Route::post('/admin/complaints/{id}/viewed', [\App\Http\Controllers\OrderComplaintController::class, 'markViewed'])->name('admin.complaints.viewed');
    Route::post('/admin/complaints/{id}/reply', [\App\Http\Controllers\OrderComplaintController::class, 'reply'])->name('admin.complaints.reply');

    // Ingredients
    Route::get('/admin/ingredients', [\App\Http\Controllers\AdminIngredientController::class, 'index']);
    Route::post('/admin/ingredients', [\App\Http\Controllers\AdminIngredientController::class, 'store']);
    Route::put('/admin/ingredients/{id}', [\App\Http\Controllers\AdminIngredientController::class, 'update']);
    Route::delete('/admin/ingredients/{id}', [\App\Http\Controllers\AdminIngredientController::class, 'destroy']);
    Route::get('/admin/inventory', function () { return view('admin.inventory'); });

    // Products
    Route::get('/admin/add_product', function () { return view('admin.add_product'); });
    Route::get('/admin/product', function (\Illuminate\Http\Request $request) {
        if (!check_permission('view_products')) {
            return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        }
        return app('App\Http\Controllers\ProductController')->index($request);
    });
    Route::get('/admin/products', function () {
        $toppings = \App\Models\Topping::all();
        return view('admin.products', compact('toppings'));
    });
    Route::get('/admin/product/{product}/recipe', [\App\Http\Controllers\ProductController::class, 'recipe']);
    Route::post('/admin/product/{product}/recipe', [\App\Http\Controllers\ProductController::class, 'updateRecipe']);
    Route::post('/admin/product/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('create_products')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->store($request);
    });
    Route::post('/admin/product/{product}/update', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        if (!check_permission('edit_products')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->update($request, $product);
    });
    Route::post('/admin/product/{product}/delete', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        if (!check_permission('delete_products')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->destroy($product);
    });
    Route::post('/admin/product/{product}/sizes', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        if (!check_permission('edit_products')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->syncSizes($request, $product);
    });

    // Toppings
    Route::post('/admin/toppings', function (\Illuminate\Http\Request $request) {
        \App\Models\Topping::create([
            'name' => $request->name,
            'price' => $request->price,
            'status' => $request->has('status')
        ]);
        return back()->with('success', 'Topping added successfully.');
    });
    Route::post('/admin/toppings/{id}', function (\Illuminate\Http\Request $request, $id) {
        $topping = \App\Models\Topping::find($id);
        if ($topping) {
            $topping->update([
                'name' => $request->name,
                'price' => $request->price,
                'status' => $request->has('status')
            ]);
        }
        return back()->with('success', 'Topping updated successfully.');
    });
    Route::post('/admin/toppings/{id}/delete', function ($id) {
        $topping = \App\Models\Topping::find($id);
        if ($topping) $topping->delete();
        return back()->with('success', 'Topping deleted successfully.');
    });

    // Sizes
    Route::post('/admin/size/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('create_sizes')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->storeSize($request);
    });
    Route::post('/admin/size/{size}/update', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
        if (!check_permission('edit_sizes')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->updateSize($request, $size);
    });
    Route::post('/admin/size/{size}/delete', function (\Illuminate\Http\Request $request, \App\Models\Size $size) {
        if (!check_permission('delete_sizes')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->destroySize($size);
    });

    // Categories
    Route::post('/admin/category/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('create_categories')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->storeCategory($request);
    });
    Route::post('/admin/category/{category}/update', function (\Illuminate\Http\Request $request, \App\Models\Category $category) {
        if (!check_permission('edit_categories')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->updateCategory($request, $category);
    });
    Route::post('/admin/category/{category}/delete', function (\Illuminate\Http\Request $request, \App\Models\Category $category) {
        if (!check_permission('delete_categories')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\ProductController')->destroyCategory($category);
    });

    // User Management
    Route::get('/admin/users', function (\Illuminate\Http\Request $request) {
        if (!check_permission('view_users')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\UserController')->index($request);
    });
    Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
        if (session('role_code') !== 'admin') return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\UserController')->store($request);
    });
    Route::post('/admin/users/{user}/role', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        if (!check_permission('assign_roles')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\UserController')->updateRole($request, $user);
    });
    Route::post('/admin/users/{user}/password', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        return app('App\Http\Controllers\UserController')->updatePassword($request, $user);
    });
    Route::post('/admin/users/{user}/toggle-status', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        return app('App\Http\Controllers\UserController')->toggleStatus($request, $user);
    });
    Route::post('/admin/users/{user}/toggle-restriction', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        return app('App\Http\Controllers\UserController')->toggleRestriction($request, $user);
    });

    // Roles and Permissions
    Route::get('/admin/roles', function () {
        if (!check_permission('view_roles')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\RoleController')->index();
    });
    Route::post('/admin/roles/update', function (\Illuminate\Http\Request $request) {
        if (!check_permission('manage_permissions')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\RoleController')->updatePermissions($request);
    });
    Route::post('/admin/roles/store', function (\Illuminate\Http\Request $request) {
        if (!check_permission('manage_permissions')) return redirect('/login/admin')->with('error', 'Tài khoản của bạn không có quyền truy cập.');
        return app('App\Http\Controllers\RoleController')->store($request);
    });

    // Promotions, Reports, Reviews
    Route::get('/admin/promotions', function () { return view('admin.promotions'); });
    Route::get('/admin/reports', function (\Illuminate\Http\Request $request) { 
        $period = $request->query('period', 'all');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Base query for orders
        $ordersQuery = \Illuminate\Support\Facades\DB::table('orders')
            ->whereIn('orders.status', ['completed', 'delivered']);

        // Apply Date Filter
        if ($period === 'custom') {
            if ($startDate && $endDate) {
                $ordersQuery->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            } elseif ($startDate) {
                $ordersQuery->where('orders.created_at', '>=', $startDate . ' 00:00:00');
            } elseif ($endDate) {
                $ordersQuery->where('orders.created_at', '<=', $endDate . ' 23:59:59');
            }
        } elseif ($period === 'today') {
            $ordersQuery->where('orders.created_at', '>=', now()->startOfDay());
            $ordersQuery->where('orders.created_at', '<=', now()->endOfDay());
        } elseif ($period === 'week') {
            $ordersQuery->where('orders.created_at', '>=', now()->startOfWeek());
        } elseif ($period === 'month') {
            $ordersQuery->where('orders.created_at', '>=', now()->startOfMonth());
        } elseif ($period === 'year') {
            $ordersQuery->where('orders.created_at', '>=', now()->startOfYear());
        }

        // Clone for aggregations
        $totalRevenue = (clone $ordersQuery)->sum('orders.total_amount');
        $totalOrders = (clone $ordersQuery)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Total Items Sold
        $totalItemsSold = (clone $ordersQuery)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum('order_items.quantity');
            
        // Unique customers
        $uniqueCustomers = (clone $ordersQuery)->distinct('orders.customer_id')->count('orders.customer_id');

        // Chart Data: Group by Date via Collections (Cross DB compatible)
        $ordersForChart = (clone $ordersQuery)->select('orders.total_amount', 'orders.created_at')->get();
        
        $format = ($period === 'year') ? 'Y-m' : 'Y-m-d';
        
        $chartGrouped = $ordersForChart->groupBy(function($item) use ($format) {
            return \Carbon\Carbon::parse($item->created_at)->format($format);
        })->map(function($group) {
            return $group->sum('total_amount');
        })->sortKeys();
        
        $chartLabels = $chartGrouped->keys()->toArray();
        $chartValues = $chartGrouped->values()->toArray();

        // Category Mix
        $categoryMixRaw = (clone $ordersQuery)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('categories.name')
            ->get();
            
        $catLabels = $categoryMixRaw->pluck('name')->toArray();
        $catData = $categoryMixRaw->pluck('total_qty')->toArray();

        // Top Products (reuse order_items logic)
        $queryTopProducts = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('product_sizes', 'order_items.product_size_id', '=', 'product_sizes.id')
            ->join('products', 'product_sizes.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->select(
                'products.id',
                'products.name',
                'products.image',
                \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'),
                \Illuminate\Support\Facades\DB::raw('SUM(order_items.total_price) as total_revenue')
            );

        if ($period === 'custom') {
            if ($startDate && $endDate) {
                $queryTopProducts->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            } elseif ($startDate) {
                $queryTopProducts->where('orders.created_at', '>=', $startDate . ' 00:00:00');
            } elseif ($endDate) {
                $queryTopProducts->where('orders.created_at', '<=', $endDate . ' 23:59:59');
            }
        } elseif ($period === 'today') {
            $queryTopProducts->where('orders.created_at', '>=', now()->startOfDay());
            $queryTopProducts->where('orders.created_at', '<=', now()->endOfDay());
        } elseif ($period === 'week') {
            $queryTopProducts->where('orders.created_at', '>=', now()->startOfWeek());
        } elseif ($period === 'month') {
            $queryTopProducts->where('orders.created_at', '>=', now()->startOfMonth());
        } elseif ($period === 'year') {
            $queryTopProducts->where('orders.created_at', '>=', now()->startOfYear());
        }

        $limit = $request->query('limit', 5);

        $topProductsQuery = $queryTopProducts->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold');
            
        if ($limit !== 'all') {
            $topProductsQuery->limit((int)$limit);
        }
        
        $topProducts = $topProductsQuery->get();
            
        return view('admin.reports', compact(
            'topProducts', 'period', 'totalRevenue', 'totalOrders', 'avgOrderValue', 
            'totalItemsSold', 'uniqueCustomers', 'chartLabels', 'chartValues', 'catLabels', 'catData'
        )); 
    });
    Route::get('/admin/reviews', [\App\Http\Controllers\ReviewController::class, 'index']);
    Route::post('/admin/reviews/{review}/status', [\App\Http\Controllers\ReviewController::class, 'updateStatus']);
    Route::post('/admin/reviews/{review}/reply', [\App\Http\Controllers\ReviewController::class, 'reply']);

    // Vouchers
    Route::get('/admin/voucher', [\App\Http\Controllers\VoucherController::class, 'index']);
    Route::get('/admin/voucher/add', [\App\Http\Controllers\VoucherController::class, 'create']);
    Route::post('/admin/voucher/store', [\App\Http\Controllers\VoucherController::class, 'store']);
    Route::get('/admin/voucher/{voucher}/edit', [\App\Http\Controllers\VoucherController::class, 'edit']);
    Route::post('/admin/voucher/{voucher}/update', [\App\Http\Controllers\VoucherController::class, 'update']);
    Route::post('/admin/voucher/{voucher}/delete', [\App\Http\Controllers\VoucherController::class, 'destroy']);

    // Table Calls
    Route::get('/admin/table-calls/pending', function() {
        if (!session('user_id')) return response()->json([]);
        $calls = \Illuminate\Support\Facades\DB::table('table_calls')
            ->join('restaurant_tables', 'table_calls.table_id', '=', 'restaurant_tables.id')
            ->leftJoin('table_areas', 'restaurant_tables.area_id', '=', 'table_areas.id')
            ->leftJoin('floors', 'table_areas.floor_id', '=', 'floors.id')
            ->where('table_calls.status', 'pending')
            ->select('table_calls.*', 'restaurant_tables.table_name as table_name', 'table_areas.name as area_name', 'floors.name as floor_name')
            ->get();
        return response()->json($calls);
    });
    Route::post('/admin/table-calls/{id}/resolve', function($id) {
        if (!session('user_id')) return response()->json(['success' => false], 403);
        \Illuminate\Support\Facades\DB::table('table_calls')->where('id', $id)->update([
            'status' => 'resolved',
            'updated_at' => now()
        ]);
        return response()->json(['success' => true]);
    });

    // Backup & Restore
    Route::get('/admin/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index']);
    Route::post('/admin/backup/create', [\App\Http\Controllers\Admin\BackupController::class, 'create']);
    Route::post('/admin/backup/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore']);
    Route::post('/admin/backup/upload', [\App\Http\Controllers\Admin\BackupController::class, 'upload']);
    Route::get('/admin/backup/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download']);
    Route::delete('/admin/backup/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'destroy']);

    // Delivery Settings
    Route::get('/admin/delivery-settings', [\App\Http\Controllers\Admin\DeliverySettingController::class, 'index']);
    Route::post('/admin/delivery-settings', [\App\Http\Controllers\Admin\DeliverySettingController::class, 'update']);


    // Restaurant Tables Management
    Route::get('/admin/tables', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'index']);
    Route::get('/admin/tables/status-data', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'statusData']);
    Route::post('/admin/tables/floors', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'storeFloor']);
    Route::delete('/admin/tables/floors/{floor}', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'destroyFloor']);
    Route::post('/admin/tables/areas', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'storeArea']);
    Route::post('/admin/tables/tables', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'storeTable']);
    Route::patch('/admin/tables/tables/{table}/status', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'updateTableStatus']);
    Route::patch('/admin/tables/tables/{table}/position', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'updatePosition']);
    Route::delete('/admin/tables/tables/{table}', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'destroyTable']);
    Route::post('/admin/tables/merge', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'mergeTables']);
    Route::post('/admin/tables/unmerge', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'unmergeTables']);
});

