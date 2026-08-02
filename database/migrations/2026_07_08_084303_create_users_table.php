<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->string('username',50)->unique();

            $table->string('password');

            $table->string('email')->unique();

            $table->string('phone',20)->unique();

            $table->string('avatar',500)->nullable();

            $table->boolean('status')
                ->default(true);

            $table->rememberToken();

            $table->timestamp('last_login_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};