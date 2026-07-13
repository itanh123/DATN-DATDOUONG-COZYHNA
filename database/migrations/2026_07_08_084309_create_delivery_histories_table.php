<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('shipper_id')
                ->constrained('shipper_profiles')
                ->cascadeOnDelete();

            $table->enum('status',[
                'assigned',
                'picked_up',
                'delivering',
                'completed',
                'failed'
            ]);

            $table->text('note')->nullable();

            $table->decimal('latitude',10,7)->nullable();

            $table->decimal('longitude',10,7)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_histories');
    }
};