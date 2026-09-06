<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeasurementUnit;
use App\Models\Ingredient;
use App\Models\ProductSize;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        RecipeIngredient::truncate();
        Recipe::truncate();
        Ingredient::truncate();
        MeasurementUnit::truncate();
        Schema::enableForeignKeyConstraints();

        $units = [
            'ml' => MeasurementUnit::create(['name' => 'Milliliter', 'symbol' => 'ml']),
            'g' => MeasurementUnit::create(['name' => 'Gram', 'symbol' => 'g']),
            'pack' => MeasurementUnit::create(['name' => 'Gói', 'symbol' => 'gói']),
            'slice' => MeasurementUnit::create(['name' => 'Lát', 'symbol' => 'lát']),
        ];

        $ingsData = [
            'Sữa tươi' => 'ml',
            'Trân châu đường đen' => 'g',
            'Kem trứng' => 'g',
            'Sữa dừa' => 'ml',
            'Bột cacao' => 'g',
            'Bột matcha' => 'g',
            'Hồng trà' => 'ml',
            'Lục trà gạo rang' => 'ml',
            'Lục trà lài' => 'ml',
            'Nước cốt chanh' => 'ml',
            'Mứt đào' => 'g',
            'Đào ngâm' => 'slice',
            'Sả' => 'g',
            'Mứt vải' => 'g',
            'Vải ngâm' => 'slice',
            'Mứt xoài' => 'g',
            'Sữa chua' => 'g',
            'Trái cây tươi' => 'g',
            'Ngũ cốc' => 'g',
            'Chè sầu' => 'g',
            'Chè dừa non' => 'g',
            'Cà phê espresso' => 'ml',
            'Sữa đặc' => 'ml',
            'Mỳ cay' => 'pack',
            'Xúc xích' => 'g',
            'Thịt bò' => 'g',
            'Hải sản' => 'g',
            'Bánh pizza' => 'pack',
            'Bánh gạo Tokbokki' => 'g',
            'Đường nước' => 'ml',
            'Đá viên' => 'g',
            'Cam tươi' => 'slice',
            'Nước cốt cam' => 'ml',
            'Nước cốt dứa' => 'ml',
            'Nước ép chanh leo' => 'ml',
            'Nước dưa hấu' => 'ml',
            'Xoài tươi' => 'g',
        ];

        $ingredients = [];
        foreach ($ingsData as $name => $unitSym) {
            $ingredients[$name] = Ingredient::create([
                'unit_id' => $units[$unitSym]->id,
                'code' => strtoupper(Str::random(6)),
                'name' => $name,
                'current_stock' => 10000,
                'minimum_stock' => 1000,
                'status' => true,
            ]);
        }

        $productSizes = ProductSize::with('product', 'size')->get();

        foreach ($productSizes as $ps) {
            $productName = $ps->product->name;
            $sizeName = $ps->size->name; // M, L, XL
            
            $multiplier = 1.0;
            if ($sizeName === 'L') $multiplier = 1.3;
            if ($sizeName === 'XL') $multiplier = 1.6;

            $recipe = Recipe::create([
                'product_size_id' => $ps->id,
                'code' => strtoupper(Str::random(8)),
                'name' => "Công thức $productName ($sizeName)",
                'preparation_time' => 5,
                'status' => true,
            ]);

            $recipeIngredients = [];

            // Add ice and sugar by default for drinks
            $isDrink = true;
            if (str_contains($productName, 'Mỳ Cay') || str_contains($productName, 'Pizza') || str_contains($productName, 'Tokbokki')) {
                $isDrink = false;
            }

            if ($isDrink) {
                $recipeIngredients['Đá viên'] = 150 * $multiplier;
                $recipeIngredients['Đường nước'] = 20 * $multiplier;
            }

            // Logic to map product to ingredients
            if (str_contains($productName, 'Sữa Tươi Trân Châu Đường Đen')) {
                $recipeIngredients['Sữa tươi'] = 150 * $multiplier;
                $recipeIngredients['Trân châu đường đen'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Kem Trứng Nướng TCDD')) {
                $recipeIngredients['Sữa tươi'] = 120 * $multiplier;
                $recipeIngredients['Kem trứng'] = 40 * $multiplier;
                $recipeIngredients['Trân châu đường đen'] = 40 * $multiplier;
            } elseif (str_contains($productName, 'Sữa Dừa Nướng TCDD')) {
                $recipeIngredients['Sữa dừa'] = 150 * $multiplier;
                $recipeIngredients['Trân châu đường đen'] = 40 * $multiplier;
            } elseif (str_contains($productName, 'Cacao Kem Trứng TCDD')) {
                $recipeIngredients['Bột cacao'] = 20 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 100 * $multiplier;
                $recipeIngredients['Kem trứng'] = 30 * $multiplier;
                $recipeIngredients['Trân châu đường đen'] = 30 * $multiplier;
            } elseif (str_contains($productName, 'Matcha TCDD')) {
                $recipeIngredients['Bột matcha'] = 15 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 120 * $multiplier;
                $recipeIngredients['Trân châu đường đen'] = 40 * $multiplier;
            } elseif (str_contains($productName, 'Trà Sữa Nướng')) {
                $recipeIngredients['Hồng trà'] = 120 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Trà Sữa Gạo Rang')) {
                $recipeIngredients['Lục trà gạo rang'] = 120 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Hồng Trà Sữa')) {
                $recipeIngredients['Hồng trà'] = 150 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 40 * $multiplier;
            } elseif (str_contains($productName, 'Trà Sữa Matcha')) {
                $recipeIngredients['Lục trà lài'] = 100 * $multiplier;
                $recipeIngredients['Bột matcha'] = 15 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Trà Sữa Xoài')) {
                $recipeIngredients['Lục trà lài'] = 120 * $multiplier;
                $recipeIngredients['Mứt xoài'] = 40 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 30 * $multiplier;
            } elseif (str_contains($productName, 'Trà Chanh')) {
                $recipeIngredients['Lục trà lài'] = 150 * $multiplier;
                $recipeIngredients['Nước cốt chanh'] = 20 * $multiplier;
            } elseif (str_contains($productName, 'Trà Đào Cam Sả')) {
                $recipeIngredients['Hồng trà'] = 120 * $multiplier;
                $recipeIngredients['Mứt đào'] = 30 * $multiplier;
                $recipeIngredients['Đào ngâm'] = 2 * $multiplier; // slices
                $recipeIngredients['Cam tươi'] = 1 * $multiplier;
                $recipeIngredients['Sả'] = 10 * $multiplier;
            } elseif (str_contains($productName, 'Trà Đào')) {
                $recipeIngredients['Hồng trà'] = 150 * $multiplier;
                $recipeIngredients['Mứt đào'] = 30 * $multiplier;
                $recipeIngredients['Đào ngâm'] = 2 * $multiplier;
            } elseif (str_contains($productName, 'Trà Vải')) {
                $recipeIngredients['Hồng trà'] = 150 * $multiplier;
                $recipeIngredients['Mứt vải'] = 30 * $multiplier;
                $recipeIngredients['Vải ngâm'] = 3 * $multiplier;
            } elseif (str_contains($productName, 'Trà Hoa Quả Nhiệt Đới')) {
                $recipeIngredients['Lục trà lài'] = 120 * $multiplier;
                $recipeIngredients['Trái cây tươi'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Yogurt Hoa Quả Dầm Ngũ Cốc')) {
                $recipeIngredients['Sữa chua'] = 100 * $multiplier;
                $recipeIngredients['Trái cây tươi'] = 60 * $multiplier;
                $recipeIngredients['Ngũ cốc'] = 30 * $multiplier;
                unset($recipeIngredients['Đường nước']);
                unset($recipeIngredients['Đá viên']);
            } elseif (str_contains($productName, 'Sữa Chua Đá')) {
                $recipeIngredients['Sữa chua'] = 100 * $multiplier;
                $recipeIngredients['Sữa đặc'] = 20 * $multiplier;
            } elseif (str_contains($productName, 'Sữa Chua Nha Đam')) {
                $recipeIngredients['Sữa chua'] = 100 * $multiplier;
            } elseif (str_contains($productName, 'Chè Sầu')) {
                $recipeIngredients['Chè sầu'] = 150 * $multiplier;
                unset($recipeIngredients['Đường nước']);
            } elseif (str_contains($productName, 'Chè Dừa Non')) {
                $recipeIngredients['Chè dừa non'] = 150 * $multiplier;
                unset($recipeIngredients['Đường nước']);
            } elseif (str_contains($productName, 'Nước Ép Cam')) {
                $recipeIngredients['Nước cốt cam'] = 150 * $multiplier;
            } elseif (str_contains($productName, 'Nước Ép Dứa')) {
                $recipeIngredients['Nước cốt dứa'] = 150 * $multiplier;
            } elseif (str_contains($productName, 'Nước Ép Chanh Leo')) {
                $recipeIngredients['Nước ép chanh leo'] = 150 * $multiplier;
            } elseif (str_contains($productName, 'Nước Dưa Hấu')) {
                $recipeIngredients['Nước dưa hấu'] = 150 * $multiplier;
            } elseif (str_contains($productName, 'Sinh Tố Xoài')) {
                $recipeIngredients['Xoài tươi'] = 80 * $multiplier;
                $recipeIngredients['Sữa đặc'] = 30 * $multiplier;
                $recipeIngredients['Sữa tươi'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Đen Đá')) {
                $recipeIngredients['Cà phê espresso'] = 40 * $multiplier;
            } elseif (str_contains($productName, 'Nâu Đá') || str_contains($productName, 'Bạc Xỉu')) {
                $recipeIngredients['Cà phê espresso'] = 30 * $multiplier;
                $recipeIngredients['Sữa đặc'] = 30 * $multiplier;
            } elseif (str_contains($productName, 'Cốt Dừa')) {
                $recipeIngredients['Cà phê espresso'] = 30 * $multiplier;
                $recipeIngredients['Sữa dừa'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Cà Phê Trứng')) {
                $recipeIngredients['Cà phê espresso'] = 40 * $multiplier;
                $recipeIngredients['Kem trứng'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Mỳ Cay')) {
                $recipeIngredients['Mỳ cay'] = 1 * $multiplier;
                if (str_contains($productName, 'Xúc Xích')) $recipeIngredients['Xúc xích'] = 50 * $multiplier;
                if (str_contains($productName, 'Bò')) $recipeIngredients['Thịt bò'] = 50 * $multiplier;
                if (str_contains($productName, 'Hải Sản')) $recipeIngredients['Hải sản'] = 50 * $multiplier;
            } elseif (str_contains($productName, 'Pizza')) {
                $recipeIngredients['Bánh pizza'] = 1 * $multiplier;
                $recipeIngredients['Phô mai'] = 30 * $multiplier;
                $recipeIngredients['Xúc xích'] = 30 * $multiplier;
            } elseif (str_contains($productName, 'Tokbokki')) {
                $recipeIngredients['Bánh gạo Tokbokki'] = 100 * $multiplier;
            }

            foreach ($recipeIngredients as $ingName => $qty) {
                if (isset($ingredients[$ingName])) {
                    $ing = $ingredients[$ingName];
                    RecipeIngredient::create([
                        'recipe_id' => $recipe->id,
                        'ingredient_id' => $ing->id,
                        'unit_id' => $ing->unit_id,
                        'quantity' => $qty,
                    ]);
                }
            }
        }
        
        echo "Successfully seeded MeasurementUnits, Ingredients, Recipes, and RecipeIngredients!\n";
    }
}
