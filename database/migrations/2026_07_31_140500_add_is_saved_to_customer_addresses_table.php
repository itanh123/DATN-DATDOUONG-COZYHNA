<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customer_addresses')) {
            Schema::table('customer_addresses', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_addresses', 'is_saved')) {
                    $table->boolean('is_saved')->default(true);
                }
            });
        }
    }

    public function down(): void
    {
    }
};
