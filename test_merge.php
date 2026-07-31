<?php
$request = Request::create('/admin/tables/merge', 'POST', ['primary_table_id' => 3, 'table_ids' => [1, 3]]);
$request->headers->set('Accept', 'application/json');
echo app(\App\Http\Controllers\Admin\RestaurantTableController::class)->mergeTables($request)->getContent();
