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
        Schema::create('boq_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('construction_site_id')->constrained('construction_sites')->cascadeOnDelete();
            $table->foreignId('tender_id')->constrained('tenders')->cascadeOnDelete();
            $table->string('title_en');
            $table->string('title_ur');
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Ensure each tender has only one BOQ
            $table->unique('tender_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq_masters');
    }
};
