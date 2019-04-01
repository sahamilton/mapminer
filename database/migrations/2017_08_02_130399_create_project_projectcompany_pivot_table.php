<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectProjectcompanyPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_projectcompany', function (Blueprint $table) {
            $table->integer('project_id')->unsigned()->index();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->integer('projectcompany_id')->unsigned()->index();
            $table->string('type');
            $table->foreign('projectcompany_id')->references('id')->on('projectcompany')->onDelete('cascade');
            $table->primary(['project_id', 'projectcompany_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_projectcompany');
    }
}
