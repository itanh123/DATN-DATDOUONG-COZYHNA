<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $this->call([
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        UserSeeder::class,
        CategorySeeder::class,
        SizeSeeder::class,
        MeasurementUnitSeeder::class,
        SupplierSeeder::class,
        VoucherSeeder::class,
    ]);
}
}
