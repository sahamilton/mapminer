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
            $table->bigIncrements('id');
            $table->string('person_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('primary_email')->unique();
            $table->string('business_title');
            $table->string('job_code');
            $table->string('job_profile');
            $table->string('management_level');
            $table->date('current_hire_date')->nullable();
            $table->string('home_zip_code')->nullable();
            $table->string('location_name')->nullable();
            $table->string('country')->nullable();
            $table->string('cost_center');
            $table->string('service_line');
            $table->string('company');
            $table->string('manager_name');
            $table->string('manager_email_address')->index();
            $table->integer('source_id')->nullable();
            $table->softDeletes();
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
