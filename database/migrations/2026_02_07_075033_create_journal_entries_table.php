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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_voucher_id');
            $table->unsignedBigInteger('credit_detail_account_id')->constrained();
            $table->unsignedBigInteger('debit_detail_account_id')->constrained();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('detail_description_en')->nullable();
            $table->string('detail_description_ur')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('journal_voucher_id')->references('id')->on('journal_vouchers')->cascadeOnDelete();
            $table->foreign('credit_detail_account_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
            $table->foreign('debit_detail_account_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
