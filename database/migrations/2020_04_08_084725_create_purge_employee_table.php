<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurgeEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purge_employee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('person_user_name');
            $table->string('worker_type');
            $table->string('business_title');
            $table->string('job_title');
            $table->string('location_name');
            $table->string('cost_center');
            $table->string('service_line');
            $table->string('company');
            $table->string('manager_name');
            $table->date('termination_date');
            $table->date('date_time_complete');
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
        Schema::dropIfExists('purge_employee');
    }
}
