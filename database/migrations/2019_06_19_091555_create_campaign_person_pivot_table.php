<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignPersonPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'salesactivity_person', function (Blueprint $table) {
                $table->integer('salesactivity_id')->unsigned()->index();
               
                $table->integer('person_id')->unsigned()->index();
                $table->string('role');
                $table->primary(['salesactivity_id', 'person_id']);
            }
        );
        Schema::table(
            'salesactivity_person', function (Blueprint $table) {
              
                $table->foreign('salesactivity_id')->references('id')->on('salesactivity')->onDelete('cascade');
               
                $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
                
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
        Schema::drop('salesactivity_person');
    }
}
