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
        Schema::create('contractor_bills', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contractor_account_id')->constrained('detail_accounts')->cascadeOnDelete();

            // Bill Details
            $table->string('bill_no')->unique();
            $table->date('bill_date');
            $table->decimal('amount', 18, 2);
            $table->text('remarks')->nullable();

            // Status: draft, verified, partial_paid, paid, cancelled
            $table->string('status')->default('draft');

            // Accounting Reference
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('id')->on('journal_vouchers')->nullableOnDelete();

            // Verification
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->foreign('verified_by')->references('id')->on('users')->nullableOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tender_id');
            $table->index('work_order_id');
            $table->index('contractor_account_id');
            $table->index('status');
            $table->index('bill_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_bills');
    }
};
