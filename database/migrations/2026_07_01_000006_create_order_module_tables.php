<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('discount_type', 20)->nullable();
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->decimal('minimum_order', 12, 2)->nullable();
            $table->decimal('maximum_discount', 12, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->integer('quantity')->nullable();
            $table->integer('used')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer_profiles');
            $table->foreignId('address_id')->nullable()->constrained('customer_addresses');
            $table->foreignId('table_session_id')->nullable()->constrained('table_sessions');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations');
            $table->foreignId('shipper_id')->nullable()->constrained('shipper_profiles');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers');
            $table->string('code', 30)->unique()->nullable();
            $table->string('order_code', 30)->nullable();
            $table->string('order_source', 30)->nullable();
            $table->string('order_type', 30)->nullable();
            $table->string('order_status', 30)->nullable();
            $table->string('status', 30)->nullable();
            $table->string('payment_method', 30)->nullable();
            $table->string('receiver_name', 255)->nullable();
            $table->string('receiver_phone', 20)->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('kitchen_note')->nullable();
            $table->text('customer_note')->nullable();
            $table->text('note')->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('shipping_fee', 12, 2)->nullable();
            $table->decimal('tax_amount', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('estimated_completed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->foreignId('product_size_id')->nullable()->constrained('product_sizes');
            $table->string('product_name', 255)->nullable();
            $table->string('size_name', 50)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->nullable();
            $table->decimal('final_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('order_item_toppings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items');
            $table->foreignId('topping_id')->nullable()->constrained('toppings');
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30)->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->string('transaction_code', 100)->nullable();
            $table->string('gateway', 30)->nullable();
            $table->string('payment_method', 30)->nullable();
            $table->string('method', 30)->nullable();
            $table->string('payment_status', 30)->nullable();
            $table->string('status', 30)->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->text('gateway_response')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_item_toppings');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('vouchers');
    }
};
