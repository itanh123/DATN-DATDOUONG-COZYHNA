<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default delivery settings
        DB::table('settings')->insert([
            [
                'key' => 'store_address',
                'value' => 'Hà Nội',
                'description' => 'Địa chỉ cửa hàng',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'fee_per_km',
                'value' => '5000',
                'description' => 'Phí giao hàng mỗi km (VNĐ)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'max_delivery_radius',
                'value' => '10',
                'description' => 'Bán kính tối đa để giao hàng (km)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'min_order_amount',
                'value' => '300000',
                'description' => 'Giá trị đơn hàng tối thiểu để giao (VNĐ)',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
