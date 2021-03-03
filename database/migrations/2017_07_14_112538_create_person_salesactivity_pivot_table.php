<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePersonSalesactivityPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_salesactivity', function (Blueprint $table) {
            $table->integer('person_id')->unsigned()->index();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->integer('salesactivity_id')->unsigned()->index();
            $table->foreign('salesactivity_id')->references('id')->on('salesactivity')->onDelete('cascade');
            $table->primary(['person_id', 'salesactivity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('person_salesactivity');
    }
}
