<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ImageSeeder extends Seeder
{
    public function run()
    {
        $products = Product::whereNull('image')->get();
        foreach ($products as $p) {
            $name = mb_strtolower($p->name);
            
            if (str_contains($name, 'trà sữa')) {
                $p->image = 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400';
            } elseif (str_contains($name, 'cafe') || str_contains($name, 'cà phê') || str_contains($name, 'bạc xỉu') || str_contains($name, 'nâu') || str_contains($name, 'đen') || str_contains($name, 'latte') || str_contains($name, 'cacao')) {
                $p->image = 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400';
            } elseif (str_contains($name, 'trà')) {
                $p->image = 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400';
            } elseif (str_contains($name, 'nước ép') || str_contains($name, 'sinh tố') || str_contains($name, 'soda') || str_contains($name, 'macchiato')) {
                $p->image = 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400';
            } elseif (str_contains($name, 'chè') || str_contains($name, 'sữa chua') || str_contains($name, 'kem')) {
                $p->image = 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400';
            } else {
                // Food / snacks
                $p->image = 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400';
            }
            
            $p->save();
        }
        
        echo "Updated " . $products->count() . " products with images.\n";
    }
}
