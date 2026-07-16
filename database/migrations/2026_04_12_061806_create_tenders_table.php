<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('construction_site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contractee_account_id')->constrained('detail_accounts')->cascadeOnDelete();
            $table->foreignId('contractor_account_id')->constrained('detail_accounts')->cascadeOnDelete();
            $table->foreignId('revenue_account_id')->constrained('detail_accounts')->cascadeOnDelete();
            $table->foreignId('expense_account_id')->constrained('detail_accounts')->cascadeOnDelete();

            // Titles
            $table->string('title_en');
            $table->string('title_ur');

            // Descriptions
            $table->text('description_en')->nullable();
            $table->text('description_ur')->nullable();

            // Work Type
            $table->string('work_type')->nullable();

            // Cost
            $table->decimal('estimated_cost', 18, 2)->nullable();

            // Dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Status: draft, approved, in_progress, completed
            $table->string('status')->default('draft');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
