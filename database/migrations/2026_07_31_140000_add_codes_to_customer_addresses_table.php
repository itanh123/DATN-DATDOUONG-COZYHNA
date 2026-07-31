<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('province_code', 20)->nullable()->after('province');
            $table->string('district_code', 20)->nullable()->after('district');
            $table->string('ward_code', 20)->nullable()->after('ward');
        });
    }

    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn(['province_code', 'district_code', 'ward_code']);
        });
    }
};
