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
        Schema::create('stock_ledger', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->index();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('invoice_id');
            $table->string('party_title_en');
            $table->string('party_title_ur');
            $table->string('description_en')->nullable();
            $table->string('description_ur')->nullable();
            $table->string('document_number');
            $table->double('stock_in_quantity');
            $table->double('stock_out_quantity');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledger');
    }
};
