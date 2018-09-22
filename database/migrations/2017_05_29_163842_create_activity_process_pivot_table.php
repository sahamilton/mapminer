<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityProcessPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_process_vertical', function (Blueprint $table) {
            $table->integer('activity_id')->unsigned()->index();
            $table->foreign('activity_id')->references('id')->on('salesactivity')->onDelete('cascade');
            
            $table->integer('salesprocess_id')->unsigned()->index();
            $table->foreign('salesprocess_id')->references('id')->on('salesprocess')->onDelete('cascade');

            $table->integer('vertical_id')->unsigned()->index();
            $table->foreign('vertical_id')->references('id')->on('searchfilters')->onDelete('cascade');
            $table->primary(['activity_id','vertical_id', 'salesprocess_id'],'primary_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activity_process_vertical');
    }
}
