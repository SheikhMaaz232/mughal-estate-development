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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();

            // Foreign key relationship with cities table
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');

            // Foreign key relationship with tehsils table
            $table->unsignedBigInteger('tehsil_id');
            $table->foreign('tehsil_id')
                ->references('id')
                ->on('tehsils')
                ->onDelete('cascade');

            $table->string('name_en');
            $table->string('name_ur');
            $table->string('area_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
