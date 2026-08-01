<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('product_reviews')) {
            return;
        }

        Schema::disableForeignKeyConstraints();
        DB::table('product_reviews')->truncate();
        Schema::enableForeignKeyConstraints();

        $user = DB::table('users')->where('email', 'khachhang@gmail.com')->first();
        $admin = DB::table('users')->where('email', 'admin@gmail.com')->first();
        $products = DB::table('products')->get();
        $orders = DB::table('orders')->get();

        if (!$user || $products->isEmpty()) {
            return;
        }

        $reviews = [
            [
                'user_id' => $user->id,
                'product_id' => $products[0]->id,
                'order_id' => $orders->first()?->id,
                'rating' => 5,
                'comment' => 'Sữa tươi trân châu đường đen cực kỳ thơm ngon, trân châu dẻo ngọt vừa vặn!',
                'admin_reply' => 'Cảm ơn bạn đã ủng hộ Cozy Hna! Chúc bạn một ngày ngọt ngào ạ <3',
                'status' => 'approved',
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'product_id' => $products[min(1, count($products) - 1)]->id,
                'order_id' => $orders->skip(1)->first()?->id,
                'rating' => 5,
                'comment' => 'Trà đào cam sả thanh mát, miếng đào giòn ngọt. Đóng gói rất cẩn thận!',
                'admin_reply' => 'Cozy Hna cảm ơn bạn nhiều ạ, lần sau hãy thử thêm topping kem trứng nhé!',
                'status' => 'approved',
                'created_at' => now()->subDays(1),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'product_id' => $products[min(2, count($products) - 1)]->id,
                'order_id' => null,
                'rating' => 4,
                'comment' => 'Kem bơ ngon béo ngậy, giao hàng nhanh. Nếu có thêm dừa khô thì tuyệt vời hơn.',
                'admin_reply' => 'Cảm ơn góp ý quý báu của bạn, shop sẽ thêm lựa chọn dừa khô trong menu sắp tới nhé!',
                'status' => 'approved',
                'created_at' => now()->subHours(5),
                'updated_at' => now(),
            ],
        ];

        DB::table('product_reviews')->insert($reviews);
    }
}
