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
        Schema::create('goods_received_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id');
            $table->unsignedBigInteger('product_id');
            $table->double('po_quantity');
            $table->double('received_qty');
            $table->double('balance');
            $table->string('detail_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('master_id')->references('id')->on('goods_received_note_masters')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_note_details');
    }
};
