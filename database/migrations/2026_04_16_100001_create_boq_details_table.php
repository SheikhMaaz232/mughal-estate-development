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
        Schema::create('boq_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boq_master_id')->constrained('boq_masters')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->cascadeOnDelete();
            $table->decimal('quantity', 18, 4)->default(0);
            $table->decimal('rate', 18, 2)->default(0);
            $table->decimal('gross_amount', 18, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq_details');
    }
};
