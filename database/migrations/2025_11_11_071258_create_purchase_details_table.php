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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_master_id');
            $table->unsignedBigInteger('product_id');
            $table->double('quantity');
            $table->double('price');
            $table->double('amount');
            $table->text('detail_remarks_en')->nullable();
            $table->text('detail_remarks_ur')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('purchase_master_id')->references('id')->on('purchase_masters')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
