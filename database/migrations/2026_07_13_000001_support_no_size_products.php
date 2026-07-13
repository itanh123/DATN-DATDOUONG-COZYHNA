<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Support products without sizes:
 * - cart_items.product_size_id  → nullable
 * - cart_items.product_id       → new FK (always filled)
 * - cart_items.unit_price       → new (price snapshot at add time)
 * - products.base_price         → new (used when product has no sizes)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Add base_price to products
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('base_price', 12, 2)->nullable()->after('status')
                ->comment('Price used when product has no sizes');
        });

        // 2. Modify cart_items: make product_size_id nullable, add product_id & unit_price
        Schema::table('cart_items', function (Blueprint $table) {
            // Drop the existing NOT NULL FK constraint first
            $table->dropForeign(['product_size_id']);
            $table->unsignedBigInteger('product_size_id')->nullable()->change();
            $table->foreign('product_size_id')
                ->references('id')->on('product_sizes')
                ->nullOnDelete();

            // Add product_id (always required)
            $table->foreignId('product_id')
                ->nullable()
                ->after('cart_id')
                ->constrained('products')
                ->nullOnDelete();

            // Add unit_price snapshot
            $table->decimal('unit_price', 12, 2)->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'unit_price']);

            $table->dropForeign(['product_size_id']);
            $table->unsignedBigInteger('product_size_id')->nullable(false)->change();
            $table->foreign('product_size_id')
                ->references('id')->on('product_sizes')
                ->cascadeOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('base_price');
        });
    }
};
