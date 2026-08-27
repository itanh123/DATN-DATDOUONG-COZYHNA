<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('measurement_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('symbol', 20)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->nullable();
            $table->string('name', 255)->nullable();
            $table->string('contact_person', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('tax_code', 30)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_account', 100)->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('unit_id')->constrained('measurement_units');
            $table->string('code', 30)->unique()->nullable();
            $table->string('name', 255)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('image', 255)->nullable();
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('maximum_stock', 10, 2)->nullable();
            $table->decimal('reorder_level', 10, 2)->nullable();
            $table->integer('expiry_warning_days')->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_size_id')->unique()->constrained('product_sizes');
            $table->string('code', 30)->unique()->nullable();
            $table->string('name', 255)->nullable();
            $table->integer('preparation_time')->default(0);
            $table->decimal('estimated_cost', 12, 2)->default(0);
            $table->text('instruction')->nullable();
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->nullable()->constrained('recipes');
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients');
            $table->foreignId('unit_id')->nullable()->constrained('measurement_units');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->integer('step_order')->default(1);
            $table->boolean('is_optional')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->string('code', 30)->unique()->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->date('order_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('status', 30)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders');
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('total_price', 12, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients');
            $table->string('transaction_type', 30)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('measurement_units');
            $table->decimal('before_quantity', 10, 2)->nullable();
            $table->decimal('after_quantity', 10, 2)->nullable();
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->text('note')->nullable();
            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('measurement_units');    }
};
