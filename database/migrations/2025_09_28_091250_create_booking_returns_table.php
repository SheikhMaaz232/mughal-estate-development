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
        Schema::create('booking_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('detail_account_id')->nullable();
            $table->unsignedBigInteger('receivable_detail_account_id')->nullable();
            $table->unsignedBigInteger('cancellation_charges_account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('cash_bank_account');
            $table->decimal('percentage_value');
            $table->string('status');
            $table->string('remarks')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('booking_applications')->cascadeOnDelete();
            $table->foreign('detail_account_id')->references('id')->on('detail_accounts')->nullable()->cascadeOnDelete();
            $table->foreign('receivable_detail_account_id')->references('id')->on('detail_accounts')->nullable()->cascadeOnDelete();
            $table->foreign('cancellation_charges_account_id')->references('id')->on('detail_accounts')->nullable()->cascadeOnDelete();
            $table->foreign('cash_bank_account')->references('id')->on('detail_accounts')->cascadeOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->nullable()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_returns');
    }
};
