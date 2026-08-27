<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            MeasurementUnitSeeder::class,
            SupplierSeeder::class,
            IngredientSeeder::class,
            ChichuMenuSeeder::class,
            RecipeSeeder::class,
            ToppingSeeder::class,
            RestaurantLayoutSeeder::class,
            VoucherSeeder::class,
            BannerSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
