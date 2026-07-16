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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_head_id');
            $table->unsignedBigInteger('control_head_id');
            $table->unsignedBigInteger('sub_head_id');
            $table->unsignedBigInteger('sub_sub_head_id');
            $table->unsignedBigInteger('sub_sub_sub_head_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('road_id')->nullable();
            $table->integer('front_id')->nullable();
            $table->double('amount_in_pkr')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('unit_no')->nullable();
            $table->string('block')->nullable();
            $table->string('code')->unique();
            $table->double('kanal')->nullable();
            $table->double('marla')->nullable();
            $table->double('front_width')->nullable();
            $table->double('length')->nullable();
            $table->string('front_width2')->nullable();
            $table->string('length2')->nullable();
            $table->double('total_marla')->nullable();
            $table->double('square_feet')->nullable();
            $table->double('total_square_feet')->nullable();
            $table->string('status');
            $table->string('image')->nullable();
            $table->string('name_en');
            $table->string('name_ur');
            $table->text('termsAndConditions')->nullable();
            $table->text('type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)
            $table->foreign('main_head_id')->references('id')->on('main_heads');
            $table->foreign('control_head_id')->references('id')->on('control_heads');
            $table->foreign('sub_head_id')->references('id')->on('sub_heads');
            $table->foreign('sub_sub_head_id')->references('id')->on('sub_sub_heads');
            $table->foreign('sub_sub_sub_head_id')->references('id')->on('sub_sub_sub_heads');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('road_id')->references('id')->on('road_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
