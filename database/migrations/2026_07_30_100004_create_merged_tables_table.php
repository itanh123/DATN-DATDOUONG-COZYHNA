<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merged_tables', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('capacity')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('merged_table_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merged_table_id')->constrained('merged_tables')->cascadeOnDelete();
            $table->foreignId('table_id')->constrained('restaurant_tables')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false); // bàn chính hay bàn phụ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merged_table_items');
        Schema::dropIfExists('merged_tables');
    }
};
