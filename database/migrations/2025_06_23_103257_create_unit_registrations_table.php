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

        Schema::create('unit_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('road_id');
            $table->integer('front_id');
            $table->unsignedBigInteger('volume_unit');
            $table->unsignedBigInteger('covering_unit');
            $table->string('volume');
            $table->string('covering');
            $table->string('actual_volume');
            $table->string('actual_covering');
            $table->string('phase');
            $table->string('unit_no');
            $table->string('unit_name_en');
            $table->string('unit_name_ur');
            $table->double('kanal');
            $table->double('marla');
            $table->double('total_marla');
            $table->double('yard');
            $table->string('status');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)\
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('road_id')->references('id')->on('road_categories');
            $table->foreign('volume_unit')->references('id')->on('units');
            $table->foreign('covering_unit')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_registrations');
    }
};
