<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('customer_id')->nullable()->constrained('customer_profiles');
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->integer('rating')->nullable();
            $table->text('comment')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('status', 30)->nullable();
            $table->text('reply')->nullable();
            $table->text('admin_reply')->nullable();
            $table->foreignId('reply_by')->nullable()->constrained('users');
            $table->timestamp('reply_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items');
            $table->foreignId('customer_id')->nullable()->constrained('customer_profiles');
            $table->string('complaint_type', 100)->nullable();
            $table->string('reason', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('status', 30)->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_complaints');
        Schema::dropIfExists('product_reviews');
    }
};
