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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('action');  // e.g., created, updated, deleted
            $table->string('model_type');  // e.g., App\Models\User
            $table->unsignedBigInteger('model_id');  // ID of the model being audited
            $table->text('changes')->nullable();  // Store changed data (if any)
            $table->timestamps();  // Action timestamp
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
