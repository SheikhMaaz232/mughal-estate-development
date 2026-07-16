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
        Schema::create('work_progress_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_progress_id');
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->decimal('completed_qty', 12, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('work_progress_id')->references('id')->on('work_progress')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_progress_details');
    }
};
