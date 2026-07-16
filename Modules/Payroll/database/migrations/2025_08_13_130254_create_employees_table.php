<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('first_name_en');
            $table->string('first_name_ur');
            $table->string('last_name_en')->nullable();
            $table->string('last_name_ur')->nullable();
            $table->string('father_name_en')->nullable();
            $table->string('father_name_ur')->nullable();
            $table->string('cnic', 15)->unique();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced'])->nullable();
            $table->foreignId('department_id')->nullable();
            $table->foreignId('designation_id')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->string('profile_picture')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
