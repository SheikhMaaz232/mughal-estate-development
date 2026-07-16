<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('company_id');
            $table->text('name_en');
            $table->text('name_ur');
            $table->double('square_feet');
            $table->text('description_en');
            $table->text('description_ur');
            $table->string('phase_en')->nullable();
            $table->string('phase_ur', 255)->nullable();
            $table->text('address_en');
            $table->text('address_ur');
            $table->string('project_map');
            $table->double('roads_area');
            $table->double('public_buildings_area');
            $table->double('miscellaneous_area');
            $table->double('park_area');
            $table->double('cemetery_area');
            $table->double('mosque_area');
            $table->double('social_waste_area');
            $table->double('disposal_area');
            $table->double('commercial_plots_area');
            $table->double('residential_plots_area');
            $table->double('total_area');
            $table->timestamps();

            $table->index('group_id');
            $table->index('company_id');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
