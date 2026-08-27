<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ToppingSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('toppings')->truncate();
        Schema::enableForeignKeyConstraints();

        $toppings = [
            [
                'code' => 'TOP-TCND',
                'name' => 'Trân Châu Đường Đen',
                'description' => 'Trân châu dẻo thơm ngấm vị đường đen đậm đà',
                'price' => 10000,
                'max_quantity' => 5,
                'status' => true,
            ],
            [
                'code' => 'TOP-TCTT',
                'name' => 'Trân Châu Trắng 3Q',
                'description' => 'Trân châu 3Q giòn sần sật',
                'price' => 10000,
                'max_quantity' => 5,
                'status' => true,
            ],
            [
                'code' => 'TOP-KTN',
                'name' => 'Kem Trứng Nướng',
                'description' => 'Lớp kem trứng béo ngậy nướng thơm lừng',
                'price' => 12000,
                'max_quantity' => 3,
                'status' => true,
            ],
            [
                'code' => 'TOP-PDF',
                'name' => 'Pudding Flan',
                'description' => 'Pudding trứng mềm mịn',
                'price' => 10000,
                'max_quantity' => 3,
                'status' => true,
            ],
            [
                'code' => 'TOP-TTC',
                'name' => 'Thạch Trái Cây',
                'description' => 'Thạch trái cây thanh mát',
                'price' => 8000,
                'max_quantity' => 5,
                'status' => true,
            ],
            [
                'code' => 'TOP-SS',
                'name' => 'Sương Sáo',
                'description' => 'Sương sáo đen giải nhiệt',
                'price' => 8000,
                'max_quantity' => 5,
                'status' => true,
            ],
            [
                'code' => 'TOP-TD',
                'name' => 'Thạch Dừa',
                'description' => 'Thạch dừa giòn ngọt',
                'price' => 8000,
                'max_quantity' => 5,
                'status' => true,
            ],
            [
                'code' => 'TOP-SX',
                'name' => 'Sốt Xoài Tươi',
                'description' => 'Sốt xoài cát nguyên chất',
                'price' => 10000,
                'max_quantity' => 3,
                'status' => true,
            ],
        ];

        foreach ($toppings as $topping) {
            Topping::create($topping);
        }
    }
}
