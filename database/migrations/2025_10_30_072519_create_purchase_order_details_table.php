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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedBigInteger('purchase_order_master_id');
            $table->integer('product_id');
            $table->double('quantity');
            $table->double('price');
            $table->double('amount');
            $table->string('detail_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_order_master_id')->references('id')->on('purchase_orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
