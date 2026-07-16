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
        Schema::table('cash_receipt_vouchers', function (Blueprint $table) {
            $table->string('transaction_type')->after('description_ur')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_receipt_vouchers', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });
    }
};
