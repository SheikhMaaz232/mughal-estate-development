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
        Schema::create('booking_partner_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('partner_relation_id');
            $table->string('partner_name_en');
            $table->string('partner_name_ur');
            $table->string('partner_father_name_en');
            $table->string('partner_father_name_ur');
            $table->string('partner_cnic_no');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('booking_applications')->cascadeOnDelete();
            $table->foreign('partner_relation_id')->references('id')->on('relations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_partner_details');
    }
};
