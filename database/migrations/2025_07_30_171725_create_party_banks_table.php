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
        Schema::create('party_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('account_title');
            $table->string('account_number');
            $table->string('branch_code');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys (optional, assuming related tables exist)
            $table->foreign('party_id')->references('id')->on('parties');
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_banks');
    }
};
