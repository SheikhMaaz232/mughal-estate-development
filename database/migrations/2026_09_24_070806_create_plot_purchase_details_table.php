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
        Schema::create('plot_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plot_purchase_master_id');
            $table->unsignedBigInteger('product_id');
            $table->double('size');
            $table->double('per_marla_rate');
            $table->double('amount');
            $table->text('detail_remarks_en')->nullable();
            $table->text('detail_remarks_ur')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plot_purchase_master_id')->references('id')->on('plot_purchase_masters')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_purchase_details');
    }
};
