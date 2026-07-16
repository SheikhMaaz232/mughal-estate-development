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
        Schema::table('booking_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('receivable_dealer_id')
                ->nullable()
                ->after('dealer_id');

            $table->foreign('receivable_dealer_id')
                ->references('id')
                ->on('detail_accounts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_applications', function (Blueprint $table) {
            $table->dropForeign(['receivable_dealer_id']);
            $table->dropColumn('receivable_dealer_id');
        });
    }
};
