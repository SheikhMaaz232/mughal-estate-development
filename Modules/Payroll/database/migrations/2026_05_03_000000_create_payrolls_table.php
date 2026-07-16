<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->string('month', 7);
            $table->integer('year');
            $table->integer('days_in_month')->default(0);
            $table->integer('total_worked_days')->default(0);
            $table->integer('total_absent_days')->default(0);
            $table->integer('total_leave_days')->default(0);
            $table->integer('total_holiday_days')->default(0);
            $table->integer('total_late_minutes')->default(0);
            $table->integer('total_early_leave_minutes')->default(0);
            $table->integer('total_overtime_minutes')->default(0);
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('daily_rate', 12, 2)->default(0);
            $table->decimal('minute_rate', 12, 4)->default(0);
            $table->decimal('absence_deduction_amount', 12, 2)->default(0);
            $table->decimal('late_early_deduction_amount', 12, 2)->default(0);
            $table->decimal('overtime_amount', 12, 2)->default(0);
            $table->decimal('allowance_adjustment', 12, 2)->default(0);
            $table->decimal('deduction_adjustment', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
