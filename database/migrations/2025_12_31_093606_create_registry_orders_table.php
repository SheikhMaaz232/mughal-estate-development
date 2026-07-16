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
        Schema::create('registry_orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('party_id');
            $table->string('fard_id')->nullable();
            $table->string('relation');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
            $table->foreign('booking_id')->references('id')->on('booking_applications')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registry_orders');
    }
};
