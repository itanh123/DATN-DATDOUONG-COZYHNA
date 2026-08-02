<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->string('order_code',30)->unique();

            $table->foreignId('customer_id')
                ->constrained('customer_profiles')
                ->cascadeOnDelete();

            $table->foreignId('address_id')
                ->nullable()
                ->constrained('customer_addresses')
                ->nullOnDelete();

            $table->foreignId('shipper_id')
                ->nullable()
                ->constrained('shipper_profiles')
                ->nullOnDelete();

            $table->foreignId('voucher_id')
                ->nullable()
                ->constrained('vouchers')
                ->nullOnDelete();

            $table->decimal('subtotal',12,2)->default(0);

            $table->decimal('discount_amount',12,2)->default(0);

            $table->decimal('shipping_fee',12,2)->default(0);

            $table->decimal('total_amount',12,2);

            $table->enum('payment_method',[
                'cash',
                'momo',
                'vnpay',
                'bank'
            ]);

            $table->enum('status',[
                'pending',
                'confirmed',
                'preparing',
                'shipping',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('note')->nullable();

            $table->timestamp('ordered_at')->nullable();

            $table->timestamps();

            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};