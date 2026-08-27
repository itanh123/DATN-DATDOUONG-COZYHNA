<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('merged_tables')) {
            Schema::create('merged_tables', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->foreignId('primary_table_id')->constrained('restaurant_tables')->onDelete('cascade');
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
