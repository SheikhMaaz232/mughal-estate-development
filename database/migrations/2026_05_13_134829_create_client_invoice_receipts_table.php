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
        Schema::create('client_invoice_receipts', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('client_invoice_id');
            $table->unsignedBigInteger('voucher_id'); // Can be BRV or CRV

            // Receipt Details
            $table->decimal('amount', 18, 2);

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->foreign('client_invoice_id')->references('id')->on('client_invoices')->onDelete('cascade');
            $table->foreign('voucher_id')->references('id')->on('journal_vouchers')->onDelete('restrict');
            $table->index('client_invoice_id');
            $table->index('voucher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_invoice_receipts');
    }
};
