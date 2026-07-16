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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('detail_account_id');
            $table->string('contact_person')->nullable();
            $table->string('status', 250);
            $table->text('remarks')->nullable();
            $table->double('gross_total');
            $table->double('tax_amount')->nullable();
            $table->double('shipping_amount')->nullable();
            $table->double('other_amount')->nullable();
            $table->double('total_amount');
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
        Schema::dropIfExists('purchase_orders');
    }
};
