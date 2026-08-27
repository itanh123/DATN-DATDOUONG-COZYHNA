<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // --- SIZES ---
        $sizes = [
            ['name' => 'S',  'volume_ml' => 250,  'description' => 'Nhỏ (250ml)'],
            ['name' => 'M',  'volume_ml' => 350,  'description' => 'Vừa (350ml)'],
            ['name' => 'L',  'volume_ml' => 500,  'description' => 'Lớn (500ml)'],
        ];
        foreach ($sizes as $s) {
            Size::firstOrCreate(['name' => $s['name']], $s);
        }

        $sizeS = Size::where('name', 'S')->first();
        $sizeM = Size::where('name', 'M')->first();
        $sizeL = Size::where('name', 'L')->first();

        // --- CATEGORIES ---
        $catTea   = Category::firstOrCreate(['name' => 'Trà Sữa'],       ['description' => 'Các loại trà sữa', 'status' => true]);
        $catCoffee= Category::firstOrCreate(['name' => 'Cà Phê'],        ['description' => 'Cà phê rang xay', 'status' => true]);
        $catFruit = Category::firstOrCreate(['name' => 'Trà Trái Cây'],  ['description' => 'Trà trái cây tươi', 'status' => true]);
        $catJuice = Category::firstOrCreate(['name' => 'Nước Ép'],       ['description' => 'Nước ép tươi', 'status' => true]);

        // --- PRODUCTS ---
        $products = [
            // Trà Sữa
            [
                'category'    => $catTea,
                'code'        => 'TS001',
                'name'        => 'Trà Sữa Trân Châu Đen',
                'description' => 'Trà sữa thơm béo kết hợp trân châu đen dai ngon. Hương vị chuẩn Đài Loan.',
                'image'       => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400',
                'prices'      => ['S' => 35000, 'M' => 45000, 'L' => 55000],
                'default'     => 'M',
            ],
            [
                'category'    => $catTea,
                'code'        => 'TS002',
                'name'        => 'Trà Sữa Matcha Nhật',
                'description' => 'Trà sữa matcha nguyên chất từ Uji Nhật Bản, béo ngậy, đắng nhẹ.',
                'image'       => 'https://images.unsplash.com/photo-1545671913-b89ac1b4ac10?w=400',
                'prices'      => ['S' => 40000, 'M' => 50000, 'L' => 60000],
                'default'     => 'M',
            ],
            [
                'category'    => $catTea,
                'code'        => 'TS003',
                'name'        => 'Trà Sữa Khoai Môn',
                'description' => 'Trà sữa khoai môn thơm dẻo, màu tím đẹp mắt, vị ngọt dịu.',
                'image'       => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400',
                'prices'      => ['S' => 35000, 'M' => 45000, 'L' => 55000],
                'default'     => 'M',
            ],
            // Cà Phê
            [
                'category'    => $catCoffee,
                'code'        => 'CF001',
                'name'        => 'Cà Phê Sữa Đá',
                'description' => 'Cà phê phin truyền thống pha cùng sữa đặc, đậm đà hương thơm.',
                'image'       => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400',
                'prices'      => ['S' => 30000, 'M' => 38000, 'L' => 45000],
                'default'     => 'M',
            ],
            [
                'category'    => $catCoffee,
                'code'        => 'CF002',
                'name'        => 'Cold Brew Mật Ong Oải Hương',
                'description' => 'Cold brew ủ lạnh 12 tiếng kết hợp mật ong và hoa oải hương tự nhiên.',
                'image'       => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400',
                'prices'      => ['S' => 45000, 'M' => 55000, 'L' => 65000],
                'default'     => 'M',
            ],
            [
                'category'    => $catCoffee,
                'code'        => 'CF003',
                'name'        => 'Cappuccino',
                'description' => 'Cà phê Ý truyền thống với lớp foam sữa béo mịn và hương thơm đặc trưng.',
                'image'       => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=400',
                'prices'      => ['S' => 40000, 'M' => 50000, 'L' => 60000],
                'default'     => 'M',
            ],
            // Trà Trái Cây
            [
                'category'    => $catFruit,
                'code'        => 'TF001',
                'name'        => 'Trà Đào Cam Sả',
                'description' => 'Trà xanh kết hợp đào tươi, cam và sả thơm mát, thanh nhiệt.',
                'image'       => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400',
                'prices'      => ['S' => 32000, 'M' => 42000, 'L' => 52000],
                'default'     => 'M',
            ],
            [
                'category'    => $catFruit,
                'code'        => 'TF002',
                'name'        => 'Trà Dâu Tây Hibiscus',
                'description' => 'Trà hoa dâm bụt màu đỏ đẹp mắt kết hợp dâu tây tươi, chua ngọt thanh.',
                'image'       => 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?w=400',
                'prices'      => ['S' => 35000, 'M' => 45000, 'L' => 55000],
                'default'     => 'M',
            ],
            // Nước Ép
            [
                'category'    => $catJuice,
                'code'        => 'NE001',
                'name'        => 'Nước Ép Cam Tươi',
                'description' => 'Cam Sunkist tươi ép ngay khi đặt, giàu vitamin C, ngọt tự nhiên.',
                'image'       => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400',
                'prices'      => ['S' => 35000, 'M' => 45000, 'L' => 55000],
                'default'     => 'M',
            ],
            [
                'category'    => $catJuice,
                'code'        => 'NE002',
                'name'        => 'Nước Ép Dưa Hấu Bạc Hà',
                'description' => 'Dưa hấu ép lạnh thêm lá bạc hà tươi, cực kỳ thanh mát mùa hè.',
                'image'       => 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?w=400',
                'prices'      => ['S' => 30000, 'M' => 40000, 'L' => 50000],
                'default'     => 'M',
            ],
        ];

        $sizeMap = ['S' => $sizeS, 'M' => $sizeM, 'L' => $sizeL];

        foreach ($products as $data) {
            $product = Product::firstOrCreate(
                ['code' => $data['code']],
                [
                    'category_id' => $data['category']->id,
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'image'       => $data['image'],
                    'status'      => 1,
                ]
            );

            // Create product sizes if not exist
            if ($product->productSizes()->count() === 0) {
                foreach ($data['prices'] as $sizeName => $price) {
                    $size = $sizeMap[$sizeName] ?? null;
                    if (!$size) continue;

                    ProductSize::create([
                        'product_id'    => $product->id,
                        'size_id'       => $size->id,
                        'selling_price' => $price,
                        'cost_price'    => $price * 0.4,
                        'is_default'    => ($sizeName === $data['default']),
                        'status'        => true,
                    ]);
                }
            }
        }

        $this->command->info('✅ ProductSeeder: ' . count($products) . ' products seeded with sizes S/M/L.');
    }
}
