<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesActivityStatePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'salesactivity_state', function (Blueprint $table) {
                $table->integer('salesactivity_id')->unsigned()->index();

                $table->integer('state_id')->unsigned()->index();

                $table->primary(['salesactivity_id', 'state_id']);
            }
        );
        Schema::table(
            'salesactivity_state', function (Blueprint $table) {
                $table->foreign('salesactivity_id')->references('id')->on('salesactivity')->onDelete('cascade');

                $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salesactivity_state');
    }
}
