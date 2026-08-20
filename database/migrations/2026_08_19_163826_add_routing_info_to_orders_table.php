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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('route_duration_minutes')->nullable()->after('distance_km');
            $table->string('distance_method', 30)->nullable()->after('route_duration_minutes')->comment('routing or straight_line');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['route_duration_minutes', 'distance_method']);
        });
    }
};
