<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('flash_sale_id')
                ->constrained('flash_sales')
                ->cascadeOnDelete();

            $table->foreignId('product_size_id')
                ->constrained('product_sizes')
                ->cascadeOnDelete();

            $table->decimal('sale_price',12,2);

            $table->integer('quantity');

            $table->integer('sold')->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};