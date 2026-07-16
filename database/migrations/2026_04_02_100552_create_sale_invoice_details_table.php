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
        Schema::create('sale_invoice_details', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('sale_invoice_master_id');
            $table->unsignedBigInteger('product_id');
            $table->double('quantity');
            $table->double('price');
            $table->double('amount');
            $table->text('detail_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('sale_invoice_master_id')->references('id')->on('sale_invoices')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoice_details');
    }
};
