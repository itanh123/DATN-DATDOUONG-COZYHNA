<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipper_profiles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('full_name');

            $table->string('phone',20)->nullable();

            $table->string('license_plate',20)->nullable();

            $table->string('vehicle_type',50)->nullable();

            $table->decimal('current_lat',10,7)->nullable();

            $table->decimal('current_lng',10,7)->nullable();

            $table->string('status',20)->default('Offline');

            $table->decimal('rating',3,2)->default(5.00);

            $table->integer('total_deliveries')->default(0);

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipper_profiles');
    }
};