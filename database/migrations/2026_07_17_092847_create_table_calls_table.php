<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('table_calls')) {
            Schema::create('table_calls', function (Blueprint $table) {
                $table->id();
                $table->string('table_number');
                $table->string('status')->default('PENDING');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
    }
};
