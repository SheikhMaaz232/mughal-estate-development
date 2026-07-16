<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('land_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained('lands')->onDelete('cascade');

            $table->string('khawat_no')->nullable();
            $table->string('fard_id_no')->nullable();
            $table->string('registry_no')->nullable();
            $table->string('moza')->nullable();

            $table->decimal('murabba', 12, 4)->nullable();
            $table->decimal('acre', 12, 4)->nullable();
            $table->decimal('kanal', 12, 4)->nullable();
            $table->decimal('wigha', 12, 4)->nullable();
            $table->decimal('marla', 12, 4)->nullable();
            $table->decimal('square_feet', 14, 4)->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('land_details');
    }
}
