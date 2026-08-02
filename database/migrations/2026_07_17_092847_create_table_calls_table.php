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
        Schema::create('table_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained('dining_tables')->cascadeOnDelete();
            $table->string('status', 20)->default('pending'); // pending, resolved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_calls');
    }
};
