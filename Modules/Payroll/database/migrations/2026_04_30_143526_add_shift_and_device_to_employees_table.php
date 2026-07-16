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
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_id')->nullable()->after('id');
            $table->unsignedBigInteger('device_id')->nullable()->after('shift_id');

            $table->foreign('shift_id')
                ->references('id')
                ->on('shifts')
                ->onDelete('set null');

            $table->foreign('device_id')
                ->references('id')
                ->on('attendance_devices')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropForeign(['device_id']);
            $table->dropColumn(['shift_id', 'device_id']);
        });
    }
};
