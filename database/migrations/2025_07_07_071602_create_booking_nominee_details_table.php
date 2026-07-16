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
        Schema::create('booking_nominee_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('relation_id');
            $table->unsignedBigInteger('nominee_party_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('booking_applications')->cascadeOnDelete();
            $table->foreign('relation_id')->references('id')->on('relations')->cascadeOnDelete();
            $table->foreign('nominee_party_id')->references('id')->on('parties')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_nominee_details');
    }
};
