<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_item_toppings')) {
            Schema::create('order_item_toppings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
                $table->foreignId('topping_id')->constrained('toppings')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 12, 2);
                $table->decimal('total_price', 12, 2);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
