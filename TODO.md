# TODO

## Step 1: Update routes/web.php
- [x] Ensure `/` returns `view('client.home')`.

## Step 2: Seed roles & admin user
- [ ] Implement `database/seeders/RoleSeeder.php` to create roles: admin, staff, customer.
- [ ] Implement `database/seeders/UserSeeder.php` to create user admin@gmail.com with password admin123 and assign role admin.
- [ ] (Optional) Implement role-permission seeding only if needed by app.

## Step 3: Run seeders
- [ ] Run `php artisan db:seed` and verify records in `roles` and `users` tables.

## Step 4: Fix admin dashboard Blade layout duplication
- [x] Clean `resources/views/admin/dashboard.blade.php` so it contains ONLY `@section('content')` inner HTML.

## Step 5: Seed categories for product form
- [x] Add categories in `database/seeders/CategorySeeder.php`: Trà, Nước Hoa Quả
- [ ] Ensure product add flow loads category list and displays existing products on `/admin/product`

