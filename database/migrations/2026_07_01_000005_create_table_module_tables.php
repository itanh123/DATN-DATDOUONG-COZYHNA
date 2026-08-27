<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

        Schema::create('table_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained('floors');
            $table->string('code', 30)->nullable();
            $table->string('name', 100)->nullable();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('table_areas');
            $table->string('code', 30)->unique();
            $table->string('name', 100)->nullable();
            $table->string('table_name', 100)->nullable();
            $table->string('qr_code', 255)->nullable();
            $table->string('qr_token', 255)->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('seating_capacity')->nullable();
            $table->integer('minimum_capacity')->nullable();
            $table->string('shape', 30)->nullable();
            $table->string('status', 30)->nullable();
            $table->unsignedBigInteger('current_session_id')->nullable();
            $table->decimal('location_x', 8, 2)->nullable();
            $table->decimal('location_y', 8, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('merged_tables', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('merged_table_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merged_table_id')->nullable()->constrained('merged_tables');
            $table->foreignId('table_id')->nullable()->constrained('restaurant_tables');
        });

        Schema::create('table_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained('restaurant_tables');
            $table->foreignId('merged_table_id')->nullable()->constrained('merged_tables');
            $table->foreignId('customer_id')->nullable()->constrained('customer_profiles');
            $table->foreignId('opened_by')->nullable()->constrained('users');
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->integer('guest_count')->nullable();
            $table->string('session_status', 30)->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customer_profiles');
            $table->foreignId('table_id')->nullable()->constrained('restaurant_tables');
            $table->foreignId('merged_table_id')->nullable()->constrained('merged_tables');
            $table->string('reservation_code', 30)->unique()->nullable();
            $table->string('guest_name', 255)->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->integer('guest_count')->nullable();
            $table->timestamp('reservation_time')->nullable();
            $table->integer('expected_duration')->nullable();
            $table->string('status', 30)->nullable();
            $table->text('special_request')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('table_sessions');
        Schema::dropIfExists('merged_table_items');
        Schema::dropIfExists('merged_tables');
        Schema::dropIfExists('restaurant_tables');
        Schema::dropIfExists('table_areas');
        Schema::dropIfExists('floors');
    }
};
