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
        Schema::create('bank_receipt_vouchers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('detail_account_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('description_en')->nullable();
            $table->string('description_ur')->nullable();
            $table->double('total_amount');
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('detail_account_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
            $table->foreign('bank_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_receipt_vouchers');
    }
};
