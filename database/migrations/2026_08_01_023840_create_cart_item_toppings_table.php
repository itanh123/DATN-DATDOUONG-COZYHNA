<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cart_item_toppings')) {
            Schema::create('cart_item_toppings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_item_id')->constrained('cart_items')->onDelete('cascade');
                $table->foreignId('topping_id')->constrained('toppings')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->decimal('price', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
    }
};
