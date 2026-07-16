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
            $table->decimal('possession_fees', 15, 2)
                ->default(0)
                ->nullable(false)
                ->after('grand_total_amount');

            $table->unsignedBigInteger('possession_receivable_account')
                ->nullable()
                ->after('possession_fees');

            $table->unsignedBigInteger('operating_receivable_account')
                ->nullable()
                ->after('operating_charges');

            $table->decimal('proceeding_fees', 15, 2)
                ->default(0)
                ->nullable(false)
                ->after('possession_receivable_account');

            $table->unsignedBigInteger('proceeding_receivable_account')
                ->nullable()
                ->after('proceeding_fees');

            $table->decimal('development_charges', 15, 2)
                ->default(0)
                ->nullable(false)
                ->after('proceeding_receivable_account');

            $table->unsignedBigInteger('development_receivable_id')
                ->nullable()
                ->after('development_charges');

            $table->decimal('gst', 15, 2)
                ->default(0)
                ->nullable(false)
                ->after('development_receivable_id');

            $table->unsignedBigInteger('gst_receivable_account_id')
                ->nullable()
                ->after('gst');

            $table->decimal('sevenE_chalan', 15, 2)
                ->default(0)
                ->nullable(false)
                ->after('gst_receivable_account_id');

            $table->unsignedBigInteger('sevenE_chalan_receivable_account')
                ->nullable()
                ->after('sevenE_chalan');




            $table->foreign('possession_receivable_account')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();

            $table->foreign('proceeding_receivable_account')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();

            $table->foreign('development_receivable_id')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();
            $table->foreign('operating_receivable_account')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();

            $table->foreign('gst_receivable_account_id')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();

            $table->foreign('sevenE_chalan_receivable_account')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_applications', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeignKey('booking_applications_possession_receivable_account_foreign');
            $table->dropForeignKey('booking_applications_proceeding_receivable_account_foreign');
            $table->dropForeignKey('booking_applications_development_receivable_id_foreign');
            $table->dropForeignKey('booking_applications_operating_receivable_account_foreign');
            $table->dropForeignKey('booking_applications_gst_receivable_account_id_foreign');
            $table->dropForeignKey('booking_applications_sevenE_chalan_receivable_account_foreign');

            // Drop all columns (fees and account IDs)
            $table->dropColumn([
                'possession_fees',
                'possession_receivable_account',
                'proceeding_fees',
                'proceeding_receivable_account',
                'development_charges',
                'development_receivable_id',
                'operating_receivable_account',
                'gst',
                'gst_receivable_account_id',
                'sevenE_chalan',
                'sevenE_chalan_receivable_account'
            ]);
        });
    }
};
