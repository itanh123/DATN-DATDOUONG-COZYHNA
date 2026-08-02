<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {

            $table->id();

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->foreignId('unit_id')
                ->constrained('measurement_units')
                ->cascadeOnDelete();

            $table->string('code',30)->unique();

            $table->string('name');

            $table->string('category',100)->nullable();

            $table->decimal('current_stock',10,2)->default(0);

            $table->decimal('minimum_stock',10,2)->default(0);

            $table->decimal('cost_price',12,2)->default(0);

            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};