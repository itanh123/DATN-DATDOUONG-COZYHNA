<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('employee_code', 30)->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('citizen_id', 20)->nullable();
            $table->date('hire_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->string('emergency_contact', 255)->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
