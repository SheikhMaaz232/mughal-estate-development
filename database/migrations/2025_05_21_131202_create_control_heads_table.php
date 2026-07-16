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
        Schema::create('control_heads', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('main_head_id');
            $table->string('name_en');
            $table->string('name_ur');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)
            $table->foreign('main_head_id')->references('id')->on('main_heads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_heads');
    }
};
