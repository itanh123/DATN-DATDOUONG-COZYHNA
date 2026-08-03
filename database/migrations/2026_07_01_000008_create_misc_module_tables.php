<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('title', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->nullable()->constrained('chat_sessions');
            $table->string('role', 30)->nullable();
            $table->text('message')->nullable();
            $table->integer('token_usage')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('position', 50)->nullable();
            $table->integer('priority')->default(1);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');    }
};
