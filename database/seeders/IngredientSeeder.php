<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('recipe_ingredients')->truncate();
        DB::table('recipes')->truncate();
        DB::table('ingredients')->truncate();
        Schema::enableForeignKeyConstraints();

        $supplier = DB::table('suppliers')->first();
        $unitMl = DB::table('measurement_units')->where('symbol', 'ml')->value('id') ?? DB::table('measurement_units')->value('id');
        $unitGram = DB::table('measurement_units')->where('symbol', 'g')->value('id') ?? DB::table('measurement_units')->value('id');

        $ingredients = [
            [
                'code' => 'NL-ST',
                'name' => 'Sữa Tươi Thanh Trùng Vinamilk',
                'category' => 'Sữa & Bơ',
                'current_stock' => 50000,
                'minimum_stock' => 5000,
                'cost_price' => 35000,
                'description' => 'Sữa tươi thanh trùng 1L',
                'expiration_date' => now()->addDays(15),
                'is_fresh' => true,
                'unit_id' => $unitMl,
                'supplier_id' => $supplier?->id,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-TD',
                'name' => 'Trà Đen Thượng Hạng',
                'category' => 'Trà',
                'current_stock' => 10000,
                'minimum_stock' => 1000,
                'cost_price' => 120000,
                'description' => 'Trà đen túi 1kg pha trà sữa',
                'expiration_date' => now()->addDays(180),
                'is_fresh' => false,
                'unit_id' => $unitGram,
                'supplier_id' => $supplier?->id,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-DD',
                'name' => 'Đường Đen Hàn Quốc',
                'category' => 'Đường & Sốt',
                'current_stock' => 20000,
                'minimum_stock' => 2000,
                'cost_price' => 45000,
                'description' => 'Đường đen nhập khẩu Hàn Quốc',
                'expiration_date' => now()->addDays(365),
                'is_fresh' => false,
                'unit_id' => $unitGram,
                'supplier_id' => $supplier?->id,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-TC',
                'name' => 'Trân Châu Đen Sống',
                'category' => 'Topping Nguyên Liệu',
                'current_stock' => 15000,
                'minimum_stock' => 3000,
                'cost_price' => 30000,
                'description' => 'Trân châu thô để nấu hàng ngày',
                'expiration_date' => now()->addDays(60),
                'is_fresh' => false,
                'unit_id' => $unitGram,
                'supplier_id' => $supplier?->id,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-KB',
                'name' => 'Kem Béo Thực Vật Rich\'s',
                'category' => 'Sữa & Bơ',
                'current_stock' => 30000,
                'minimum_stock' => 5000,
                'cost_price' => 28000,
                'description' => 'Kem Rich\'s lỏng pha trà sữa',
                'expiration_date' => now()->addDays(30),
                'is_fresh' => true,
                'unit_id' => $unitMl,
                'supplier_id' => $supplier?->id,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('ingredients')->insert($ingredients);
    }
}
