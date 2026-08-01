<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        DB::table('reservations')->truncate();
        Schema::enableForeignKeyConstraints();

        $customerProfile = DB::table('customer_profiles')->first();
        $tables = DB::table('restaurant_tables')->get();

        if ($tables->isEmpty()) {
            return;
        }

        $reservations = [
            [
                'reservation_code' => 'RES-1001',
                'customer_id' => $customerProfile?->id,
                'table_id' => $tables[0]->id,
                'guest_name' => 'Nguyễn Văn Khách',
                'guest_phone' => '0977889900',
                'guest_count' => 4,
                'reservation_time' => now()->addHours(2),
                'expected_duration' => 90,
                'status' => 'confirmed',
                'special_request' => 'Chuẩn bị sẵn 4 ly nước và ghế trẻ em',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reservation_code' => 'RES-1002',
                'customer_id' => null,
                'table_id' => $tables[count($tables) - 1]->id,
                'guest_name' => 'Trần Thị Mai',
                'guest_phone' => '0911223344',
                'guest_count' => 2,
                'reservation_time' => now()->addDays(1)->setHour(19)->setMinute(0),
                'expected_duration' => 60,
                'status' => 'pending',
                'special_request' => 'Cho mình bàn gần ban công view đẹp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('reservations')->insert($reservations);
    }
}
