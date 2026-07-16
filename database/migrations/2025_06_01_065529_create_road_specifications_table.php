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
        Schema::create('road_specifications', function (Blueprint $table) {
           $table->id();

            // Foreign key relationship with cities table
            $table->unsignedBigInteger('road_category_id');
            $table->foreign('road_category_id')
                ->references('id')
                ->on('road_categories')
                ->onDelete('cascade');

            $table->string('title_en');
            $table->string('title_ur');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('road_specifications');
    }
};
