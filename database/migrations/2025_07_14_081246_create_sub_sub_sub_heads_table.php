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
        Schema::create('sub_sub_sub_heads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_head_id');
            $table->unsignedBigInteger('control_head_id');
            $table->unsignedBigInteger('sub_head_id');
            $table->unsignedBigInteger('sub_sub_head_id');
            $table->unsignedBigInteger('project_id');
            $table->string('name_en');
            $table->string('name_ur');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)
            $table->foreign('main_head_id')->references('id')->on('main_heads');
            $table->foreign('control_head_id')->references('id')->on('control_heads');
            $table->foreign('sub_head_id')->references('id')->on('sub_heads');
            $table->foreign('sub_sub_head_id')->references('id')->on('sub_sub_heads');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sub_sub_heads');
    }
};
