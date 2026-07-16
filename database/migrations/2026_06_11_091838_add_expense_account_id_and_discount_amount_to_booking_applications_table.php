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
            $table->unsignedBigInteger('expense_account_id')
                ->nullable()
                ->after('transfer_charges_account_id');

            $table->decimal('discount_amount', 15, 2)
                ->nullable()
                ->after('discount');

            $table->foreign('expense_account_id')
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
            $table->dropForeign(['expense_account_id']);
            $table->dropColumn([
                'expense_account_id',
                'discount_amount',
            ]);
        });
    }
};
