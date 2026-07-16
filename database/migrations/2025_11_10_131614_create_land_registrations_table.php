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
        Schema::create('land_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('party_account_id')->constrained('detail_accounts')->onDelete('cascade');
            $table->string('khawat_number')->nullable();
            $table->decimal('kanal', 10, 2)->default(0);
            $table->decimal('merla', 10, 2)->default(0);
            $table->decimal('square_feet', 15, 2)->default(0);
            $table->decimal('total_merla', 15, 4)->default(0);
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id', 'party_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_registrations');
    }
};
