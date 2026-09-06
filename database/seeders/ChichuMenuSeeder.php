<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\ProductSize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChichuMenuSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('product_sizes')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('sizes')->truncate();
        DB::table('toppings')->truncate();
        Schema::enableForeignKeyConstraints();

        $sizeM = Size::create(['name' => 'M']);
        $sizeL = Size::create(['name' => 'L']);
        $sizeXL = Size::create(['name' => 'XL']);

                        $data = [
            '⭐ Bán chạy' => [
                ['name' => 'Sữa Tươi Trân Châu Đường Đen', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Kem Trứng Nướng TCDD', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Dừa Nướng TCDD', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Cacao Kem Trứng TCDD', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Matcha TCDD', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
            ],
            '🧋 Trà sữa' => [
                ['name' => 'Trà Sữa Nướng', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Gạo Rang', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Hồng Trà Sữa', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Matcha', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Xoài', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
            ],
            '🍵 Trà & Trà trái cây' => [
                ['name' => 'Trà Chanh', 'sizes' => ['M' => 15000, 'L' => 20000, 'XL' => 25000]],
                ['name' => 'Trà Đào', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Đào Cam Sả', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Trà Vải', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Hoa Quả Nhiệt Đới', 'sizes' => ['M' => 30000, 'L' => 35000, 'XL' => 40000]],
            ],
            '🥤 Sữa chua & Chè' => [
                ['name' => 'Yogurt Hoa Quả Dầm Ngũ Cốc', 'sizes' => ['M' => 30000, 'L' => 35000, 'XL' => 40000]],
                ['name' => 'Sữa Chua Đá', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Sữa Chua Nha Đam', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Chè Sầu', 'sizes' => ['M' => 25000, 'L' => 30000]],
                ['name' => 'Chè Dừa Non', 'sizes' => ['M' => 20000, 'L' => 25000]],
            ],
            '🥭 Nước ép & Sinh tố' => [
                ['name' => 'Nước Ép Cam', 'sizes' => ['M' => 25000, 'L' => 30000]],
                ['name' => 'Nước Ép Dứa', 'sizes' => ['M' => 25000, 'L' => 30000]],
                ['name' => 'Nước Ép Chanh Leo', 'sizes' => ['M' => 25000, 'L' => 30000]],
                ['name' => 'Nước Dưa Hấu', 'sizes' => ['M' => 25000, 'L' => 30000]],
                ['name' => 'Sinh Tố Xoài', 'sizes' => ['M' => 30000, 'L' => 35000]],
            ],
            '☕ Cà phê & Đồ uống nóng' => [
                ['name' => 'Đen Đá', 'sizes' => ['M' => 15000, 'L' => 20000]],
                ['name' => 'Nâu Đá', 'sizes' => ['M' => 18000, 'L' => 22000]],
                ['name' => 'Bạc Xỉu', 'sizes' => ['M' => 20000, 'L' => 25000]],
                ['name' => 'Cốt Dừa', 'sizes' => ['M' => 25000, 'L' => 30000]],
                ['name' => 'Cà Phê Trứng', 'sizes' => ['M' => 30000, 'L' => 35000]],
            ],
            '🍕 Đồ ăn' => [
                ['name' => 'Mỳ Cay Xúc Xích', 'sizes' => ['M' => 40000]],
                ['name' => 'Mỳ Cay Bò', 'sizes' => ['M' => 45000]],
                ['name' => 'Mỳ Cay Hải Sản', 'sizes' => ['M' => 50000]],
                ['name' => 'Pizza 80K', 'sizes' => ['M' => 80000]],
                ['name' => 'Tokbokki', 'sizes' => ['M' => 35000]],
            ],
        ];

        foreach ($data as $catName => $products) {
            $category = Category::create([
                'name' => $catName,
                'status' => true,
            ]);

            foreach ($products as $prodData) {
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $prodData['name'],
                    'code' => strtoupper(Str::random(6)),
                    'image' => 'products/' . $prodData['name'] . '.png',
                    'status' => true,
                    'is_auto_stock' => true,
                ]);

                if (isset($prodData['sizes'])) {
                    $isFirst = true;
                    foreach ($prodData['sizes'] as $sName => $price) {
                        $size = null;
                        if ($sName === 'M') $size = $sizeM;
                        if ($sName === 'L') $size = $sizeL;
                        if ($sName === 'XL') $size = $sizeXL;

                        ProductSize::create([
                            'product_id' => $product->id,
                            'size_id' => $size ? $size->id : $sizeM->id,
                            'selling_price' => $price,
                            'is_default' => $isFirst,
                            'status' => true,
                        ]);
                        $isFirst = false;
                    }
                } elseif (isset($prodData['price'])) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_id' => $sizeM->id,
                        'selling_price' => $prodData['price'],
                        'is_default' => true,
                        'status' => true,
                    ]);
                }
            }
        }

        // Toppings
        $toppings = [
            ['name' => 'Trân Châu Đen', 'price' => 5000],
            ['name' => 'Trân Châu Trắng', 'price' => 5000],
            ['name' => 'Thạch Nha Đam', 'price' => 5000],
            ['name' => 'Thạch Phô Mai', 'price' => 10000],
            ['name' => 'Kem Cheese', 'price' => 10000],
            ['name' => 'Pudding Trứng', 'price' => 10000],
        ];
        foreach ($toppings as $top) {
            \App\Models\Topping::create([
                'code' => strtoupper(\Illuminate\Support\Str::random(6)),
                'name' => $top['name'],
                'price' => $top['price'],
                'status' => true,
            ]);
        }
    }
}
