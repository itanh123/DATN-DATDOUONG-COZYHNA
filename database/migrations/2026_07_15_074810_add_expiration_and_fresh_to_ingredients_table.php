<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ingredients')) {
            Schema::table('ingredients', function (Blueprint $table) {
                if (!Schema::hasColumn('ingredients', 'expiration_date')) {
                    $table->dateTime('expiration_date')->nullable();
                }
                if (!Schema::hasColumn('ingredients', 'is_fresh')) {
                    $table->boolean('is_fresh')->default(false);
                }
            });
        }
    }

    public function down(): void
    {
    }
};
