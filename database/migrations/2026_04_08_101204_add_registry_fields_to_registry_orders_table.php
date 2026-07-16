<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('registry_orders', function (Blueprint $table) {

            $table->decimal('registry_fees', 15, 2)->default(0)->after('relation');

            $table->unsignedBigInteger('registry_fees_receivable_account')
                ->nullable()
                ->after('registry_fees');

            $table->enum('registry_status', ['pending', 'completed', 'underprocess'])
                ->default('pending')
                ->after('registry_fees_receivable_account');

            // Optional: Foreign Key
            $table->foreign('registry_fees_receivable_account')
                ->references('id')
                ->on('detail_accounts')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('registry_orders', function (Blueprint $table) {

            $table->dropForeign(['registry_fees_receivable_account']);

            $table->dropColumn([
                'registry_fees',
                'registry_fees_receivable_account',
                'registry_status'
            ]);
        });
    }
};
