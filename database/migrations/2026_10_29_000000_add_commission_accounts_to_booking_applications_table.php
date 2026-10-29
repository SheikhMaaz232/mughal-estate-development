<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('project_dealer_id')->nullable()->after('dealer_id');
            $table->unsignedBigInteger('project_exp_account_id')->nullable()->after('project_dealer_id');
            $table->unsignedBigInteger('receivable_income_account_id')->nullable()->after('receivable_dealer_id');
            $table->unsignedBigInteger('exp_account_id')->nullable()->after('receivable_income_account_id');

            $table->foreign('project_dealer_id')->references('id')->on('detail_accounts')->nullOnDelete();
            $table->foreign('project_exp_account_id')->references('id')->on('detail_accounts')->nullOnDelete();
            $table->foreign('receivable_income_account_id')->references('id')->on('detail_accounts')->nullOnDelete();
            $table->foreign('exp_account_id')->references('id')->on('detail_accounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('booking_applications', function (Blueprint $table) {
            $table->dropForeign(['project_dealer_id']);
            $table->dropForeign(['project_exp_account_id']);
            $table->dropForeign(['receivable_income_account_id']);
            $table->dropForeign(['exp_account_id']);
            $table->dropColumn([
                'project_dealer_id',
                'project_exp_account_id',
                'receivable_income_account_id',
                'exp_account_id',
            ]);
        });
    }
};
