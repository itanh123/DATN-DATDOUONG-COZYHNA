<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('recipes') || !Schema::hasTable('recipe_ingredients')) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        DB::table('recipe_ingredients')->truncate();
        DB::table('recipes')->truncate();
        Schema::enableForeignKeyConstraints();

        $productSizes = DB::table('product_sizes')->get();
        $ingredients = DB::table('ingredients')->pluck('id', 'code');
        $unitMl = DB::table('measurement_units')->where('symbol', 'ml')->value('id') ?? DB::table('measurement_units')->value('id') ?? 1;
        $unitGram = DB::table('measurement_units')->where('symbol', 'g')->value('id') ?? DB::table('measurement_units')->value('id') ?? 1;

        if ($productSizes->isEmpty()) {
            return;
        }

        foreach ($productSizes->take(5) as $ps) {
            $product = DB::table('products')->where('id', $ps->product_id)->first();
            $recipeName = 'Công thức pha ' . ($product->name ?? 'Đồ uống');

            $recipeId = DB::table('recipes')->insertGetId([
                'name' => $recipeName,
                'product_size_id' => $ps->id,
                'preparation_time' => 5,
                'instruction' => "1. Cho 150ml sữa tươi vào ly.\n2. Thêm 30ml đường đen và 50g trân châu.\n3. Khuấy đều và thêm đá.",
                'estimated_cost' => 12000,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert recipe ingredients
            if (isset($ingredients['NL-ST'])) {
                DB::table('recipe_ingredients')->insert([
                    'recipe_id' => $recipeId,
                    'ingredient_id' => $ingredients['NL-ST'],
                    'unit_id' => $unitMl,
                    'quantity' => 150,
                    'step_order' => 1,
                    'note' => 'Sữa tươi ướp lạnh',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($ingredients['NL-DD'])) {
                DB::table('recipe_ingredients')->insert([
                    'recipe_id' => $recipeId,
                    'ingredient_id' => $ingredients['NL-DD'],
                    'unit_id' => $unitGram,
                    'quantity' => 30,
                    'step_order' => 2,
                    'note' => 'Đường đen đun dẻo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (isset($ingredients['NL-TC'])) {
                DB::table('recipe_ingredients')->insert([
                    'recipe_id' => $recipeId,
                    'ingredient_id' => $ingredients['NL-TC'],
                    'unit_id' => $unitGram,
                    'quantity' => 50,
                    'step_order' => 3,
                    'note' => 'Trân châu luộc chín dẻo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
