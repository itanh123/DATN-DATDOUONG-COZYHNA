<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('inventory_transactions')) {
            Schema::create('inventory_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
                $table->enum('transaction_type', ['IMPORT', 'EXPORT', 'ADJUSTMENT', 'WASTE'])->default('IMPORT');
                $table->decimal('quantity', 12, 3);
                $table->string('unit_name', 30);
                $table->text('reason')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
    }
};
