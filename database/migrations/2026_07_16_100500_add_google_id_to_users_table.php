<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable();
                }
                if (!Schema::hasColumn('users', 'is_restricted')) {
                    $table->boolean('is_restricted')->default(false);
                }
            });
        }
    }

    public function down(): void
    {
    }
};
