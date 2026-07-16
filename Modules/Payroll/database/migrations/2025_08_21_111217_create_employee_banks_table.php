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
        Schema::create('employee_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->integer('bank_id')->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_title', 255)->nullable();
            $table->string('iban', 34)->nullable();
            $table->string('branch_code', 20)->nullable();
            $table->enum('type', ['savings', 'current', 'salary'])->default('salary');
            $table->timestamps();
            
            $table->index(['employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_banks');
    }
};
