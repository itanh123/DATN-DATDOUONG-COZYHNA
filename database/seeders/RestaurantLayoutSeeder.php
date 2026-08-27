<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;
use App\Models\TableArea;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RestaurantLayoutSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('restaurant_tables')->truncate();
        DB::table('table_areas')->truncate();
        DB::table('floors')->truncate();
        Schema::enableForeignKeyConstraints();

        // Floor 1
        $floor1 = Floor::create([
            'code' => 'FLOOR-1',
            'name' => 'Tầng 1 - Sảnh Chính',
            'description' => 'Tầng sảnh chính đón khách',
            'display_order' => 1,
            'status' => true,
        ]);

        $area1 = TableArea::create([
            'floor_id' => $floor1->id,
            'name' => 'Khu Vực Trong Nhà',
            'code' => 'KVTN-T1',
            'description' => 'Không gian thoáng mát có điều hòa',
            'display_order' => 1,
            'status' => true,
        ]);

        $areaVip = TableArea::create([
            'floor_id' => $floor1->id,
            'name' => 'Khu Vực VIP',
            'code' => 'VIP-T1',
            'description' => 'Không gian riêng tư sang trọng',
            'display_order' => 2,
            'status' => true,
        ]);

        // Floor 2
        $floor2 = Floor::create([
            'code' => 'FLOOR-2',
            'name' => 'Tầng 2 - Ban Công & Sân Thượng',
            'description' => 'Tầng ban công ngắm cảnh',
            'display_order' => 2,
            'status' => true,
        ]);

        $area2 = TableArea::create([
            'floor_id' => $floor2->id,
            'name' => 'Ban Công Ngắm Cảnh',
            'code' => 'BC-T2',
            'description' => 'View ngoài trời thoáng đãng',
            'display_order' => 1,
            'status' => true,
        ]);

        // Tables for Floor 1 - Indoor (4x4 Grid coordinates: x=0..3, y=0..3)
        // Bàn 01 (0,0), Bàn 02 (1,0), Bàn 03 (2,0) -> Đầy đủ liên kề!
        for ($i = 1; $i <= 6; $i++) {
            $code = 'TB10' . $i;
            $x = ($i - 1) % 3;
            $y = intdiv($i - 1, 3);
            RestaurantTable::create([
                'area_id' => $area1->id,
                'code' => $code,
                'table_name' => 'Bàn ' . sprintf('%02d', $i),
                'qr_token' => Str::random(32),
                'capacity' => 4,
                'minimum_capacity' => 1,
                'shape' => 'rectangle',
                'status' => 'available',
                'location_x' => $x,
                'location_y' => $y,
                'note' => 'Bàn tiêu chuẩn 4 người',
            ]);
        }

        // Tables for Floor 1 - VIP (x=0, y=0 và x=1, y=0)
        for ($i = 1; $i <= 2; $i++) {
            $code = 'TBVIP' . $i;
            RestaurantTable::create([
                'area_id' => $areaVip->id,
                'code' => $code,
                'table_name' => 'Bàn VIP ' . sprintf('%02d', $i),
                'qr_token' => Str::random(32),
                'capacity' => 8,
                'minimum_capacity' => 4,
                'shape' => 'round',
                'status' => 'available',
                'location_x' => $i - 1,
                'location_y' => 0,
                'note' => 'Bàn VIP lớn gia đình/nhóm đông',
            ]);
        }

        // Tables for Floor 2 - Balcony (x=0..1, y=0..1)
        for ($i = 1; $i <= 4; $i++) {
            $code = 'TB20' . $i;
            $x = ($i - 1) % 2;
            $y = intdiv($i - 1, 2);
            RestaurantTable::create([
                'area_id' => $area2->id,
                'code' => $code,
                'table_name' => 'Bàn Ban Công ' . sprintf('%02d', $i),
                'qr_token' => Str::random(32),
                'capacity' => 2,
                'minimum_capacity' => 1,
                'shape' => 'square',
                'status' => 'available',
                'location_x' => $x,
                'location_y' => $y,
                'note' => 'Bàn đôi ngắm cảnh',
            ]);
        }
    }
}
