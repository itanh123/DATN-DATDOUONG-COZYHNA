<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_complaints')) {
            Schema::create('product_complaints', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('customer_id')->constrained('customer_profiles')->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->enum('status', ['PENDING', 'PROCESSING', 'RESOLVED', 'REJECTED'])->default('PENDING');
                $table->text('resolution_note')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
