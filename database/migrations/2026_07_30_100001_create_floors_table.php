<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('floors')) {
            Schema::create('floors', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->integer('display_order')->default(1);
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
