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
        Schema::create('goods_received_note_masters', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_no');
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('detail_account_id');
            $table->double('fare');
            $table->string('supplier_bill_no')->nullable();
            $table->string('unloaded_by');
            $table->string('status', 250);
            $table->string('driver_name', 250);
            $table->double('total_po_quantity');
            $table->double('total_received_quantity');
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
        Schema::dropIfExists('goods_received_note_masters');
    }
};
