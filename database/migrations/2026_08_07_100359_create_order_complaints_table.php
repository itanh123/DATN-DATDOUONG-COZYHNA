<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_complaints')) {
            Schema::create('order_complaints', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('customer_id')->constrained('customer_profiles')->onDelete('cascade');
                $table->dateTime('incident_time')->nullable();
                $table->string('target_person')->nullable();
                $table->text('description');
                $table->json('images')->nullable();
                $table->enum('status', ['PENDING', 'VIEWED', 'REPLIED'])->default('PENDING');
                $table->boolean('is_viewed_by_admin')->default(false);
                $table->text('admin_reply')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_complaints');
    }
};
