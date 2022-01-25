<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOracleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oracle', function (Blueprint $table) {
            
            $table->string('person_number')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('primary_email');
            $table->string('business_title');
            $table->string('job_code');
            $table->string('job_profile');
            $table->string('management_level');
            $table->date('current_hire_date');
            $table->string('home_zip_code');
            $table->string('location_name');
            $table->string('country');
            $table->string('cost_center');
            $table->string('service_line');
            $table->string('company');
            $table->string('manager_name');
            $table->string('manager_email_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oracle');
    }
}
