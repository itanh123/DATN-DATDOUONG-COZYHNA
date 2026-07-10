<?php

use Illuminate\Support\Facades\Route;




// Migrated UI Routes
Route::get('/', function () { return view('customer.home'); });
Route::get('/customer/auth', function () { return view('customer.auth'); });
Route::get('/customer/checkout', function () { return view('customer.checkout'); });
Route::get('/customer/contact', function () { return view('customer.contact'); });
Route::get('/customer/account', function () { return view('customer.account'); });
Route::get('/customer/orders', function () { return view('customer.orders'); });
Route::get('/customer/notifications', function () { return view('customer.notifications'); });
Route::get('/customer/product_detail', function () { return view('customer.product_detail'); });
Route::get('/admin/dashboard', function () { return view('admin.dashboard'); });
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