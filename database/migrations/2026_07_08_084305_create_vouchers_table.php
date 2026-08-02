<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {

            $table->id();

            $table->string('code',50)->unique();

            $table->string('name');

            $table->text('description')->nullable();

            $table->enum('discount_type',[
                'percent',
                'fixed'
            ]);

            $table->decimal('discount_value',12,2);

            $table->decimal('minimum_order',12,2)->default(0);

            $table->decimal('maximum_discount',12,2)->nullable();

            $table->integer('quantity');

            $table->integer('used')->default(0);

            $table->dateTime('start_date');

            $table->dateTime('end_date');

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};