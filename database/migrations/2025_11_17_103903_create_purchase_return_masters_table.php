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
        Schema::create('purchase_return_masters', function (Blueprint $table) {
            $table->id();
            $table->integer('grn_no');
            $table->integer('purchase_order_no');
            $table->integer('purchase_invoice_no');
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('detail_account_id');
            $table->string('supplier_bill_no', 20);
            $table->string('unloaded_by');
            $table->string('status', 250);
            $table->double('carriage')->nullable();
            $table->double('gross_bill');
            $table->double('tax')->nullable();
            $table->double('net_amount');
            $table->double('other_amount')->nullable();
            $table->double('total_quantity');
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
        Schema::dropIfExists('purchase_return_masters');
    }
};
