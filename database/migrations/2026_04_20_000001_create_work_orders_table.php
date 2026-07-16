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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('construction_site_id');
            $table->unsignedBigInteger('tender_id');
            $table->unsignedBigInteger('boq_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description_en')->nullable();
            $table->text('description_ur')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('construction_site_id')->references('id')->on('construction_sites')->cascadeOnDelete();
            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
            $table->foreign('boq_id')->references('id')->on('boq_masters')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
