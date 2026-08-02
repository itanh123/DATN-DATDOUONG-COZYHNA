<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->foreignId('product_size_id')
                ->unique()
                ->constrained('product_sizes')
                ->cascadeOnDelete();

            $table->integer('preparation_time')->default(0);

            $table->text('instruction')->nullable();

            $table->decimal('estimated_cost',12,2)->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};