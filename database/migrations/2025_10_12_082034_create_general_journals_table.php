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
        Schema::create('general_journals', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->index();
            $table->unsignedBigInteger('project_id');
            $table->integer('invoice_id');
            $table->unsignedBigInteger('party_id')->nullable();
            $table->unsignedBigInteger('detail_account_id');
            $table->string('description_en')->nullable();
            $table->string('description_ur')->nullable();
            $table->string('document_number');
            $table->double('debit');
            $table->double('credit');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
            $table->foreign('detail_account_id')->references('id')->on('detail_accounts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journals');
    }
};
