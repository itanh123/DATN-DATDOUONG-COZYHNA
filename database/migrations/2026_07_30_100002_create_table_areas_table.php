<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('table_areas')) {
            Schema::create('table_areas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');
                $table->string('code', 30);
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
    }
};
