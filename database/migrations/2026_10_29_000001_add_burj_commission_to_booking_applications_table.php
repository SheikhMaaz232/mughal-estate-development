<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_applications', function (Blueprint $table) {
            $table->decimal('burj_commission', 15, 2)->default(0)->after('commission');
        });
    }

    public function down(): void
    {
        Schema::table('booking_applications', function (Blueprint $table) {
            $table->dropColumn('burj_commission');
        });
    }
};
