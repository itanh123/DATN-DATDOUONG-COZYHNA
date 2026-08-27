<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('table_sessions')) {
            Schema::create('table_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('table_id')->constrained('restaurant_tables')->onDelete('cascade');
                $table->string('session_code', 50)->unique();
                $table->dateTime('start_time');
                $table->dateTime('end_time')->nullable();
                $table->enum('status', ['ACTIVE', 'CLOSED'])->default('ACTIVE');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
