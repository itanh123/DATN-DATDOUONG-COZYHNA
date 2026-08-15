<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('employee_profiles')) {
            Schema::create('employee_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('employee_code', 30)->unique();
                $table->string('department', 50)->nullable();
                $table->string('position', 50)->nullable();
                $table->date('hire_date')->nullable();
                $table->decimal('salary', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
