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
                if (!Schema::hasColumn('customer_addresses', 'province_code')) {
                    $table->string('province_code')->nullable();
                }
                if (!Schema::hasColumn('customer_addresses', 'district_code')) {
                    $table->string('district_code')->nullable();
                }
                if (!Schema::hasColumn('customer_addresses', 'ward_code')) {
                    $table->string('ward_code')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
    }
};
