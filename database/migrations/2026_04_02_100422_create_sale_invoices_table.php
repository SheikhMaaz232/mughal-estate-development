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
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_invoice_no');
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('detail_account_id');
            $table->string('status', 250);
            $table->double('gross_bill', 15, 2);
            $table->double('total_quantity', 15, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('detail_account_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
