<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Make order_items.product_size_id nullable to support products without sizes.
 * Add product_id to order_items so we always know what product was ordered.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop old NOT NULL FK
            $table->dropForeign(['product_size_id']);
            $table->unsignedBigInteger('product_size_id')->nullable()->change();
            $table->foreign('product_size_id')
                ->references('id')->on('product_sizes')
                ->nullOnDelete();

            // Add product_id for reference (no-size case)
            $table->foreignId('product_id')
                ->nullable()
                ->after('order_id')
                ->constrained('products')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropForeign(['product_size_id']);
            $table->unsignedBigInteger('product_size_id')->nullable(false)->change();
            $table->foreign('product_size_id')
                ->references('id')->on('product_sizes')
                ->cascadeOnDelete();
        });
    }
};
