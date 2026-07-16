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
        Schema::create('booking_payment_shedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('schedule_type_id');
            $table->unsignedBigInteger('schedule_period_id');
            $table->date('due_date');
            $table->integer('number');
            $table->decimal('pay_amount', 15, 2);
            $table->decimal('calculated_total_amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('booking_applications')->cascadeOnDelete();
            $table->foreign('schedule_type_id')->references('id')->on('schedule_types')->cascadeOnDelete();
            $table->foreign('schedule_period_id')->references('id')->on('schedule_periods')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payment_shedules');
    }
};
