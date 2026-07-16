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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ur');
            $table->string('father_name_en');
            $table->string('father_name_ur');
            $table->string('cnic_no');
            $table->string('ntn_no')->nullable();
            $table->string('gst_no')->nullable();
            $table->unsignedBigInteger('cast_id');
            $table->unsignedBigInteger('residential_status');
            $table->unsignedBigInteger('occupation_id');
            $table->string('cnic_front_image')->nullable();
            $table->string('cnic_back_image')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('business_name_en')->nullable();
            $table->string('business_name_ur')->nullable();
            $table->string('business_address_en')->nullable();
            $table->string('business_address_ur')->nullable();
            $table->string('home_address_en');
            $table->string('home_address_ur');
            $table->string('remarks')->nullable();
            $table->string('contact_number_1');
            $table->string('contact_number_2')->nullable();
            $table->string('whatsApp_no');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)
            $table->foreign('cast_id')->references('id')->on('casts');
            $table->foreign('residential_status')->references('id')->on('residentials');
            $table->foreign('occupation_id')->references('id')->on('occupation_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
