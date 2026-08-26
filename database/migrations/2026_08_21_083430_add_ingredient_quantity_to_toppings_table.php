<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('toppings', function (Blueprint $table) {
            $table->decimal('ingredient_quantity', 10, 2)->default(1)->after('ingredient_id')->comment('Số lượng trừ kho quy ước cho mỗi lượt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toppings', function (Blueprint $table) {
            //
        });
    }
};
