<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Trà',
                'description' => null,
                'image' => null,
                'status' => true,
            ],
            [
                'name' => 'Nước Hoa Quả',
                'description' => null,
                'image' => null,
                'status' => true,
            ],
        ];

        foreach ($categories as $data) {
            
            \DB::table('categories')->updateOrInsert(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'image' => $data['image'],
                    'status' => $data['status'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}

