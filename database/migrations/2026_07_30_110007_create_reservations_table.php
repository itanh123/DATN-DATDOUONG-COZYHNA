<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customer_profiles')->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('restaurant_tables')->nullOnDelete();
            $table->foreignId('merged_table_id')->nullable()->constrained('merged_tables')->nullOnDelete();
            $table->string('reservation_code', 30)->unique();
            $table->string('guest_name', 255)->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->integer('guest_count')->default(1);
            $table->timestamp('reservation_time')->nullable();
            $table->integer('expected_duration')->nullable(); // phút
            $table->string('status', 30)->default('pending'); // pending | confirmed | cancelled | completed
            $table->text('special_request')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
