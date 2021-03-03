<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateprojectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dodge_repnum');
            $table->string('project_title');
            $table->string('project_addr1')->nullable();
            $table->string('project_addr2')->nullable();
            $table->string('project_city')->nullable();
            $table->string('project_state')->nullable();
            $table->string('project_zipcode')->nullable();
            $table->string('project_county_name')->nullable();
            $table->string('project_county_code')->nullable();
            $table->decimal('project_lat', 20, 14)->nullable();
            $table->decimal('project_lng', 20, 14)->nullable();
            $table->string('structure_header')->nullable();
            $table->string('project_type')->nullable();
            $table->string('stage')->nullable();
            $table->string('ownership')->nullable();
            $table->string('bid_date')->nullable();
            $table->integer('start_year')->nullable();
            $table->string('start_yearmo')->nullable();
            $table->string('target_start_date')->nullable();
            $table->string('target_comp_date')->nullable();
            $table->string('work_type')->nullable();
            $table->string('status')->nullable();
            $table->string('project_value')->nullable();
            $table->string('total_project_value')->nullable();
            $table->string('value_range')->nullable();
            $table->string('ow_factor_type')->nullable();
            $table->string('ow_firm')->nullable();
            $table->string('ow_contact')->nullable();
            $table->string('ow_title')->nullable();
            $table->string('ow_addr1')->nullable();
            $table->string('ow_addr2')->nullable();
            $table->string('ow_city')->nullable();
            $table->string('ow_state')->nullable();
            $table->string('ow_zipcode')->nullable();
            $table->string('ow_county')->nullable();
            $table->string('ow_phone')->nullable();
            $table->string('gc_factor_type')->nullable();
            $table->string('gc_firm')->nullable();
            $table->string('gc_contact')->nullable();
            $table->string('gc_contact_title')->nullable();
            $table->string('gc_addr1')->nullable();
            $table->string('gc_addr2')->nullable();
            $table->string('gc_city')->nullable();
            $table->string('gc_state')->nullable();
            $table->string('gc_zipcode')->nullable();
            $table->string('gc_county')->nullable();
            $table->string('gc_phone')->nullable();

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
        Schema::dropIfExists('projects');
    }
}
