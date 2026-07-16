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
        Schema::table('general_journals', function (Blueprint $table) {
            $table->boolean('is_fee_entry')->default(false)->after('credit')->comment('Marks if entry is a fee (Possession, Proceeding, Development, GST, 7E Chalan)');
            $table->index('is_fee_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_journals', function (Blueprint $table) {
            $table->dropIndex(['is_fee_entry']);
            $table->dropColumn('is_fee_entry');
        });
    }
};
