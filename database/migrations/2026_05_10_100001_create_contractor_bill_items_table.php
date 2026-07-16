<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contractor_bill_items', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('contractor_bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('boq_item_id')->constrained('boq_details')->cascadeOnDelete();

            // Quantities & Amounts
            $table->decimal('quantity', 12, 2);
            $table->decimal('rate', 18, 2);
            $table->decimal('amount', 18, 2);

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('contractor_bill_id');
            $table->index('boq_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_bill_items');
    }
};
