<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('construction_sites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('name_en');
            $table->string('name_ur');
            $table->text('description_en')->nullable();
            $table->text('description_ur')->nullable();
            $table->text('address_en');
            $table->text('address_ur');

            $table->date('estimated_start_date')->nullable();
            $table->date('estimated_end_date')->nullable();
            $table->string('status')->default('pending'); // pending, ongoing, completed, on-hold

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('construction_sites');
    }
};
