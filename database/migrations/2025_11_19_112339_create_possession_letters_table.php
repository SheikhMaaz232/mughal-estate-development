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
        Schema::create('possession_letters', function (Blueprint $table) {
            $table->id();
            $table->string('file_no');
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('party_id');
            $table->string('east_side')->nullable();
            $table->string('east_bounded_by')->nullable();
            $table->string('west_side')->nullable();
            $table->string('west_bounded_by')->nullable();
            $table->string('south_side')->nullable();
            $table->string('south_bounded_by')->nullable();
            $table->string('north_side')->nullable();
            $table->string('north_bounded_by')->nullable();
            $table->string('status');
            $table->double('kanal');
            $table->double('marla');
            $table->double('square_feet');
            $table->double('total_marla');
            $table->double('total_square_feet');
            $table->text('special_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('possession_letters');
    }
};
