<?php

// Migration: create_property_units_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Schema::create('property_units', function (Blueprint $table) {
            // $table->id();
            // $table->unsignedBigInteger('project_id');
            // $table->unsignedBigInteger('phase_id');
            // $table->unsignedBigInteger('product_id');
            // $table->decimal('volume', 10, 2)->nullable();
            // $table->string('volume_unit')->nullable();
            // $table->decimal('covering', 10, 2)->nullable();
            // $table->string('covering_unit')->nullable();
            // $table->unsignedBigInteger('road_id')->nullable();
            // $table->unsignedBigInteger('front_id')->nullable();
            // $table->string('unit_name_en');
            // $table->string('unit_name_ur')->nullable();
            // $table->decimal('actual_volume', 10, 2)->nullable();
            // $table->string('actual_volume_unit')->nullable();
            // $table->decimal('actual_covering', 10, 2)->nullable();
            // $table->string('actual_covering_unit')->nullable();
            // $table->decimal('length', 10, 2)->nullable();
            // $table->decimal('height', 10, 2)->nullable();
            // $table->decimal('width', 10, 2)->nullable();
            // $table->decimal('sqr_feet', 10, 2)->nullable();
            // $table->decimal('kanal', 10, 2)->nullable();
            // $table->decimal('marla', 10, 2)->nullable();
            // $table->decimal('yard', 10, 2)->nullable();
            // $table->string('status')->default('active');
            // $table->timestamps();
            // $table->softDeletes();

            // // Foreign keys (optional, assuming related tables exist)
            // $table->foreign('project_id')->references('id')->on('projects');
            // $table->foreign('phase_id')->references('id')->on('phases');
            // $table->foreign('product_id')->references('id')->on('products');
            // $table->foreign('road_id')->references('id')->on('roads');
            // $table->foreign('front_id')->references('id')->on('fronts');
        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('property_units');
    }
};
