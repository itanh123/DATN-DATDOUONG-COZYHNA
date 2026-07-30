<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->string('transaction_type', 30); // IMPORT | EXPORT | ADJUST | WASTE
            $table->decimal('quantity', 10, 2);
            $table->foreignId('unit_id')->nullable()->constrained('measurement_units')->nullOnDelete();
            $table->decimal('before_quantity', 10, 2)->default(0);
            $table->decimal('after_quantity', 10, 2)->default(0);
            $table->string('reference_type', 50)->nullable(); // PURCHASE | ORDER | RECIPE | MANUAL
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
