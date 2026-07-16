<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandsTable extends Migration
{
    public function up()
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_account_id');
            $table->integer('buyer_account_id');
            $table->integer('commission_account_id');

            $table->decimal('total_murabba', 12, 4)->nullable();
            $table->decimal('total_acre', 12, 4)->nullable();
            $table->decimal('total_kanal', 12, 4)->nullable();
            $table->decimal('total_wigha', 12, 4)->nullable();
            $table->decimal('total_marla', 12, 4)->nullable();
            $table->decimal('total_square_feet', 14, 4)->nullable();

            $table->text('remarks');
            $table->integer('project_id');

            $table->decimal('commission_amount', 14, 2)->nullable();
            $table->decimal('land_amount', 16, 2);

            $table->text('terms_conditions_en')->nullable();
            $table->text('terms_conditions_ur')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lands');
    }
}
