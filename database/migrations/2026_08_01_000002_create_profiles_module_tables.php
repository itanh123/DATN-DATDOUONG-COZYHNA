<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->integer('loyalty_points')->default(0);
            $table->string('membership_level', 30)->default('Member');
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->string('favorite_category', 100)->nullable();
            $table->timestamp('last_order_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer_profiles');
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('ward', 100)->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
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

        Schema::create('shipper_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users');
            $table->string('vehicle_type', 50)->nullable();
            $table->string('license_plate', 30)->nullable();
            $table->decimal('current_lat', 10, 7)->nullable();
            $table->decimal('current_lng', 10, 7)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('total_deliveries')->nullable();
            $table->string('status', 30)->nullable()->comment('ONLINE | OFFLINE | BUSY');
            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('shipper_profiles');
        Schema::dropIfExists('employee_profiles');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customer_profiles');    }
};
