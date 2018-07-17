<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchFilterTrainingPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searchfilter_training', function (Blueprint $table) {
            $table->integer('training_id')->unsigned()->index();
            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
            $table->integer('searchfilter_id')->unsigned()->index();
            $table->foreign('searchfilter_id')->references('id')->on('searchfilters')->onDelete('cascade');
            $table->primary(['training_id', 'searchfilter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('searchfilter_training');
    }
}
