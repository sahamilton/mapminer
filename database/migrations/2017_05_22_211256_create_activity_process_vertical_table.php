<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityProcessVerticalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_process_vertical', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('activity_id')->unsigned()->index();
            $table->foreign('activity_id')->references('id')->on('salesactivity')->onDelete('cascade');
            $table->integer('vertical_id')->unsigned()->index();
            $table->foreign('vertical_id')->references('id')->on('searchfilters')->onDelete('cascade');
            $table->integer('salesprocess_id')->unsigned()->index();
            $table->foreign('salesprocess_id')->references('id')->on('salesprocess')->onDelete('cascade');
            $table->primary(['salesactivity_id','vertical_id', 'salesprocess_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_process_vertical');
    }
}
