<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
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
            $table->unsignedInteger('address_id')->nullable()->unique('RelatedAddress');
            $table->string('project_id', 60)->index('dodge_repnum');
            $table->string('project_title', 255);
            $table->string('project_county_name', 255)->nullable();
            $table->string('project_county_code', 255)->nullable();
            $table->string('structure_header', 255)->nullable();
            $table->string('project_type', 255)->nullable();
            $table->string('stage', 255)->nullable();
            $table->string('ownership', 255)->nullable();
            $table->string('bid_date', 255)->nullable();
            $table->integer('start_year')->nullable();
            $table->string('start_yearmo', 255)->nullable();
            $table->string('target_start_date', 255)->nullable();
            $table->string('target_comp_date', 255)->nullable();
            $table->string('work_type', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->float('project_value', 10)->nullable();
            $table->float('total_project_value', 10)->nullable();
            $table->string('value_range', 255)->nullable();
            $table->integer('pr_status')->nullable();
            $table->integer('serviceline_id')->default(5);
            $table->unsignedInteger('project_source_id')->index('project_source_id');
            $table->timestamps();
            $table->string('type', 10)->default('project');

            $table->unique(['project_id'], 'project_id');
            $table->unique(['project_id'], 'source_ref');
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
