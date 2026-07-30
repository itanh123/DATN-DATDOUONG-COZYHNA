<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('table_areas')->cascadeOnDelete();
            $table->string('code', 30)->unique();
            $table->string('table_name', 100);
            $table->string('qr_token', 64)->unique()->nullable();
            $table->integer('capacity')->default(4);
            $table->integer('minimum_capacity')->default(1);
            $table->enum('shape', ['round', 'square', 'rectangle'])->default('square');
            $table->enum('status', ['available', 'occupied', 'reserved', 'disabled', 'merged'])->default('available');
            $table->decimal('location_x', 8, 2)->default(0); // vị trí X trên sơ đồ (đơn vị: ô lưới)
            $table->decimal('location_y', 8, 2)->default(0); // vị trí Y trên sơ đồ (đơn vị: ô lưới)
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
