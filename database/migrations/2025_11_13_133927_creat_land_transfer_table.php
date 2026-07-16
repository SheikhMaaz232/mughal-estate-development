<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('land_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained()->onDelete('cascade');
            $table->date('transfer_date');
            $table->foreignId('registry_type_id')->constrained();
            $table->foreignId('purchaser_account_id')->constrained('detail_accounts');
            $table->foreignId('seller_account_id')->constrained('detail_accounts');
            $table->string('fard_no');
            $table->string('khawat_no');
            $table->string('khatoni_no')->nullable();
            $table->string('mushtarqa_khata')->nullable();
            $table->string('makhsoos_raqba')->nullable();
            $table->string('qitaat')->nullable();
            $table->string('saalam_khata')->nullable();
            $table->string('hissa_mutaliqa')->nullable();
            $table->string('raqba_muntaqila')->nullable();
            $table->decimal('value', 15, 2);
            $table->string('attachment_1')->nullable();
            $table->string('attachment_2')->nullable();
            $table->string('attachment_3')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('land_transfers');
    }
};