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
        Schema::create('booking_applications', function (Blueprint $table) {
            $table->id();
            $table->string('form_no');
            $table->integer('previous_booking_id')->nullable();
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('detail_account_id');
            $table->unsignedBigInteger('transfer_charges_account_id')->nullable();
            $table->decimal('transfer_charges')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('dealer_id');
            $table->date('date');
            $table->string('status');
            $table->date('operating_start_date');
            $table->decimal('operating_charges');
            $table->string('condition');
            $table->string('case');
            $table->string('care_off')->nullable();
            $table->decimal('add_value', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('commission', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('grand_total_amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
            $table->foreign('detail_account_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
            $table->foreign('transfer_charges_account_id')->references('id')->on('detail_accounts')->nullable()->cascadeOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('dealer_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_applications');
    }
};
