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
        DB::table('cart_items')->truncate();
        DB::table('carts')->truncate();
        DB::table('product_sizes')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('sizes')->truncate();
        Schema::enableForeignKeyConstraints();

        $sizeM = Size::create(['name' => 'M']);
        $sizeL = Size::create(['name' => 'L']);
        $sizeXL = Size::create(['name' => 'XL']);

        $data = [
            'SỮA TƯƠI (BEST SELLER)' => [
                ['name' => 'Sữa Tươi Trân Châu Đường Đen', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Kem Trứng Nướng TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Kem Trứng Dừa Nướng TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Dừa Nướng TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Nho TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Cacao TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Cacao Kem Trứng TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Đậu Đỏ TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sầu Riêng TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Matcha TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Matcha Xoài TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Khoai Môn TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
            ],
            'SỮA CHUA (SIGNATURE)' => [
                ['name' => 'Yogurt Hoa Quả Dầm Ngũ Cốc', 'sizes' => ['M' => 35000, 'L' => 40000]],
                ['name' => 'Sữa Chua Đỏ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua Nha Đam', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua Xoài TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua Việt Quất TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua Kiwi TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua Dâu TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Sữa Chua Chanh Leo TCĐĐ', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
            ],
            'TRÀ SỮA' => [
                ['name' => 'Trà Sữa Nướng', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Gạo Rang', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Hồng Trà Sữa', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Hồng Trà Kem Trứng Dừa Nướng', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Trà Sữa Hạt Dẻ Độc Biệt', 'sizes' => ['M' => 25000, 'L' => 30000, 'XL' => 35000]],
                ['name' => 'Trà Sữa Hokkaido', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Dưa Lưới', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
            ],
            'TRÀ SỮA VỊ' => [
                ['name' => 'Trà Sữa Matcha', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Khoai Môn', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Việt Quất', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Xoài', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Dâu Tây', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Bạc Hà', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Sữa Socola', 'sizes' => ['M' => 20000, 'L' => 25000, 'XL' => 30000]],
            ],
            'TRÀ SỮA 3 TẦNG' => [
                ['name' => 'Latte Sốt Xoài', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Latte Sốt Dâu', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Latte Sốt Việt Quất', 'sizes' => ['L' => 30000, 'XL' => 35000]],
            ],
            'MACCHIATO' => [
                ['name' => 'Hồng Trà Macchiato', 'sizes' => ['M' => 30000, 'L' => 35000]],
                ['name' => 'Việt Quất Macchiato', 'sizes' => ['M' => 30000, 'L' => 35000]],
                ['name' => 'Dưa Lưới Macchiato', 'sizes' => ['M' => 30000, 'L' => 35000]],
            ],
            'CHÈ' => [
                ['name' => 'Chè Sầu', 'price' => 30000],
                ['name' => 'Chè Dừa Non', 'price' => 25000],
                ['name' => 'Chè Trân Châu Đường Đen', 'price' => 25000],
                ['name' => 'Sữa Chua Mít', 'price' => 25000],
            ],
            'KEM' => [
                ['name' => 'Kem Vị Dừa', 'price' => 25000],
                ['name' => 'Kem Bơ Vị Dừa', 'price' => 30000],
            ],
            'TRÀ TRÁI CÂY' => [
                ['name' => 'Trà Chanh', 'sizes' => ['L' => 10000, 'XL' => 15000]],
                ['name' => 'Trà Đào', 'sizes' => ['L' => 25000, 'XL' => 30000]],
                ['name' => 'Trà Đào Cam Sả', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Trà Lài Vải', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Trà Vải Thơm Nhiệt Đới', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Trà Hoa Quả Nhiệt Đới', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Olong Cam Đào', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Olong Sen Vàng', 'sizes' => ['L' => 30000, 'XL' => 35000]],
            ],
            'NƯỚC ÉP - SINH TỐ' => [
                ['name' => 'Nước Ép Cam', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Nước Ép Dứa (Mix Cà Rốt/Hạt Chia)', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Nước Chanh Leo', 'sizes' => ['L' => 25000, 'XL' => 30000]],
                ['name' => 'Nước Dưa Hấu', 'sizes' => ['L' => 25000, 'XL' => 30000]],
                ['name' => 'Nước Cà Rốt', 'sizes' => ['L' => 25000, 'XL' => 30000]],
                ['name' => 'Sinh Tố Xoài (Mix Bơ)', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Sinh Tố Mãng Cầu (Mix Bơ)', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Sinh Tố Bơ', 'sizes' => ['L' => 30000, 'XL' => 35000]],
            ],
            'ĐÁ XAY' => [
                ['name' => 'Việt Quất Kem Cheese', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Dâu Tây Kem Cheese', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Socola Kem Cheese', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Matcha Kem Cheese', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Cookies Kem Cheese', 'sizes' => ['L' => 30000, 'XL' => 35000]],
                ['name' => 'Đá Sữa Mây Kem Cheese', 'sizes' => ['L' => 30000, 'XL' => 35000]],
            ],
            'SODA' => [
                ['name' => 'Soda Bạc Hà', 'price' => 25000],
                ['name' => 'Soda Việt Quất', 'price' => 25000],
                ['name' => 'Soda Dâu Tây', 'price' => 25000],
                ['name' => 'Soda Biển Xanh', 'price' => 25000],
            ],
            'ĐỒ UỐNG NÓNG' => [
                ['name' => 'Cacao Nóng Kem Trứng', 'price' => 30000],
                ['name' => 'Bạc Xỉu Nóng', 'price' => 25000],
                ['name' => 'Trà Đào Cam Sả Nóng', 'price' => 30000],
                ['name' => 'Đào Cam Dâu Nóng', 'price' => 25000],
                ['name' => 'Trà Gừng Mật Ong', 'price' => 25000],
                ['name' => 'Trà Gừng Ô Mai', 'price' => 25000],
            ],
            'CAFE' => [
                ['name' => 'Đen Đá / Nóng', 'price' => 20000],
                ['name' => 'Nâu Đá / Nóng', 'price' => 25000],
                ['name' => 'Bạc Xỉu', 'price' => 25000],
                ['name' => 'Cafe Muối', 'price' => 30000],
                ['name' => 'Cafe Cốt Dừa', 'price' => 30000],
                ['name' => 'Cà Phê Trứng', 'price' => 35000],
            ],
            'MÌ CAY' => [
                ['name' => 'Mì Cay Bò', 'price' => 40000],
                ['name' => 'Mì Cay Xúc Xích', 'price' => 40000],
                ['name' => 'Mì Cay Hải Sản', 'price' => 45000],
                ['name' => 'Mì Cay Thập Cẩm', 'price' => 50000],
            ],
            'ĐỒ ĂN VẶT' => [
                ['name' => 'Nem Nướng Nha Trang', 'price' => 30000],
                ['name' => 'Khoai Lang Kén', 'price' => 25000],
                ['name' => 'Bánh Mì Nướng Muối Ớt', 'price' => 15000],
                ['name' => 'Chân Gà Sốt Thái', 'price' => 45000],
                ['name' => 'Cánh Gà KFC', 'price' => 25000],
                ['name' => 'Đùi Gà KFC', 'price' => 30000],
                ['name' => 'Nem Chua Rán', 'price' => 30000],
                ['name' => 'Khoai Tây Chiên', 'price' => 30000],
                ['name' => 'Xúc Xích', 'price' => 10000],
                ['name' => 'Hướng Dương', 'price' => 10000],
                ['name' => 'Gà Khô / Bò Khô', 'price' => 25000],
                ['name' => 'Kimbap', 'price' => 30000],
                ['name' => 'Tokbokki', 'price' => 30000],
            ],
            'PIZZA' => [
                ['name' => 'Pizza', 'price' => 80000],
            ]
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
                    'status' => true,
                    'base_price' => isset($prodData['price']) ? $prodData['price'] : null,
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
                            'size_id' => $size->id,
                            'selling_price' => $price,
                            'is_default' => $isFirst,
                            'status' => true,
                        ]);
                        $isFirst = false;
                    }
                }
            }
        }
    }
}
