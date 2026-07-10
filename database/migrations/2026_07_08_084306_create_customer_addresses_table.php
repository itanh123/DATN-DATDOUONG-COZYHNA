<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customer_profiles')
                ->cascadeOnDelete();

            $table->string('receiver_name');

            $table->string('receiver_phone',20);

            $table->string('province',100)->nullable();

            $table->string('district',100)->nullable();

            $table->string('ward',100)->nullable();

            $table->text('address')->nullable();

            $table->boolean('is_default')->default(false);

            $table->text('note')->nullable();

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};