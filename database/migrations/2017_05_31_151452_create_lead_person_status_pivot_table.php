<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadPersonStatusPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_person_status', function (Blueprint $table) {
            $table->integer('lead_id')->unsigned()->index();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            $table->integer('person_id')->unsigned()->index();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->integer('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')->on('lead_status')->onDelete('cascade');
            $table->integer('rating');
            $table->timestamps();
            $table->primary(['lead_id', 'person_id','status_id'], 'primary_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lead_status');
    }
}
