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
        Schema::create('client_invoices', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('tender_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('journal_voucher_id')->nullable();

            // Invoice Details
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->decimal('amount', 18, 2);
            $table->text('remarks_en')->nullable();
            $table->text('remarks_ur')->nullable();

            // Status
            $table->enum('status', ['draft', 'verified', 'partial_received', 'received', 'cancelled'])->default('draft');

            // Verification
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('restrict');
            $table->foreign('client_id')->references('id')->on('parties')->onDelete('restrict');
            $table->foreign('journal_voucher_id')->references('id')->on('journal_vouchers')->onDelete('restrict');
            $table->index('invoice_no');
            $table->index('tender_id');
            $table->index('status');
            $table->index('invoice_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_invoices');
    }
};
