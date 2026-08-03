<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('restaurant_tables')) {
            Schema::create('restaurant_tables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('area_id')->constrained('table_areas')->onDelete('cascade');
                $table->string('code', 30);
                $table->string('name', 100);
                $table->integer('seating_capacity')->default(4);
                $table->enum('status', ['EMPTY', 'SERVING', 'RESERVED', 'MAINTENANCE'])->default('EMPTY');
                $table->string('qr_code_path')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
    }
};
