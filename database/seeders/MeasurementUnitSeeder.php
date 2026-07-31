<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasurementUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Gram', 'symbol' => 'g'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Milliliter', 'symbol' => 'ml'],
            ['name' => 'Liter', 'symbol' => 'l'],
            ['name' => 'Hộp', 'symbol' => 'hộp'],
            ['name' => 'Chai', 'symbol' => 'chai'],
            ['name' => 'Gói', 'symbol' => 'gói'],
            ['name' => 'Quả', 'symbol' => 'quả'],
        ];

        foreach ($units as $unit) {
            \App\Models\MeasurementUnit::firstOrCreate(['symbol' => $unit['symbol']], $unit);
        }
    }
}
