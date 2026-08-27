<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->foreignId('customer_id')->nullable()->constrained('customer_profiles')->onDelete('set null');
                $table->foreignId('table_id')->nullable()->constrained('restaurant_tables')->onDelete('set null');
                $table->string('guest_name');
                $table->string('guest_phone');
                $table->dateTime('reservation_time');
                $table->integer('guest_count')->default(1);
                $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
