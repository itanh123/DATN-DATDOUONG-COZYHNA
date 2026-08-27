<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('vouchers')->truncate();
        Schema::enableForeignKeyConstraints();

        $vouchers = [
            [
                'code' => 'WELCOME10',
                'name' => 'Giảm 10% Cho Đơn Đầu Tiên',
                'description' => 'Ưu đãi dành cho khách hàng mới khi mua đồ uống tại Cozy Hna',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'minimum_order' => 50000,
                'maximum_discount' => 20000,
                'quantity' => 100,
                'used' => 0,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(30),
                'status' => true,
            ],
            [
                'code' => 'COZY50K',
                'name' => 'Giảm 50.000đ Cho Đơn Từ 200k',
                'description' => 'Khuyến mãi đặc biệt cho đơn hàng lớn',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'minimum_order' => 200000,
                'maximum_discount' => 50000,
                'quantity' => 50,
                'used' => 0,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(15),
                'status' => true,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn Phí Vận Chuyển 15.000đ',
                'description' => 'Miễn phí giao hàng cho đơn từ 100.000đ',
                'discount_type' => 'fixed',
                'discount_value' => 15000,
                'minimum_order' => 100000,
                'maximum_discount' => 15000,
                'quantity' => 200,
                'used' => 0,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(60),
                'status' => true,
            ],
            [
                'code' => 'TRATRAICAY20',
                'name' => 'Giảm 20% Trà Trái Cây',
                'description' => 'Ưu đãi giải nhiệt mùa hè',
                'discount_type' => 'percent',
                'discount_value' => 20,
                'minimum_order' => 60000,
                'maximum_discount' => 30000,
                'quantity' => 80,
                'used' => 0,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(20),
                'status' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
