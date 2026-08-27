<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('banners')) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        DB::table('banners')->truncate();
        Schema::enableForeignKeyConstraints();

        $banners = [
            [
                'title' => 'Trà Sữa Cozy Hna - Vị Trà Đậm Đà Thơm Ngon',
                'image' => 'banners/banner1.jpg',
                'link' => '/menu',
                'position' => 'hero',
                'priority' => 1,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(90),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Siêu Ưu Đãi Mùa Hè - Đồng Giá Topping 5k',
                'image' => 'banners/banner2.jpg',
                'link' => '/vouchers',
                'position' => 'hero',
                'priority' => 2,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(60),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Menu Mới - Sữa Chua Dừa & Trà Trái Cây Nhiệt Đới',
                'image' => 'banners/banner3.jpg',
                'link' => '/menu',
                'position' => 'hero',
                'priority' => 3,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(30),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('banners')->insert($banners);
    }
}
