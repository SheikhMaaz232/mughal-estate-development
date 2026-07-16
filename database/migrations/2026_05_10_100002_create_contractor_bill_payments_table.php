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
        Schema::create('contractor_bill_payments', function (Blueprint $table) {
           $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('contractor_bill_id');
            $table->unsignedBigInteger('voucher_id')->nullable(); // Links to vouchers table (BPV/CPV)
            $table->string('voucher_type')->nullable(); // 'BPV', 'CPV', etc.

            // Payment Details
            $table->decimal('amount', 18, 2);
            $table->dateTime('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // 'bank', 'cash', 'cheque', etc.
            $table->text('remarks')->nullable();

            // Status
            $table->enum('status', ['pending', 'posted', 'cancelled'])->default('pending');

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->foreign('contractor_bill_id')
                ->references('id')
                ->on('contractor_bills')
                ->onDelete('cascade');
            $table->index('voucher_id');
            $table->index('contractor_bill_id');
            $table->index('status');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_bill_payments');
    }
};
