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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_head_id');
            $table->unsignedBigInteger('control_head_id');
            $table->unsignedBigInteger('sub_head_id');
            $table->unsignedBigInteger('sub_sub_head_id');
            $table->unsignedBigInteger('sub_sub_sub_head_id');
            $table->unsignedBigInteger('measurement_unit_id')->nullable();
            $table->string('name_en', 255);
            $table->string('name_ur', 255);
            $table->string('item_image', 2048)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)
            $table->foreign('main_head_id')->references('id')->on('main_heads');
            $table->foreign('control_head_id')->references('id')->on('control_heads');
            $table->foreign('sub_head_id')->references('id')->on('sub_heads');
            $table->foreign('sub_sub_head_id')->references('id')->on('sub_sub_heads');
            $table->foreign('sub_sub_sub_head_id')->references('id')->on('sub_sub_sub_heads');
            $table->foreign('measurement_unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
