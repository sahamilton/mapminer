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
        Schema::create('campaign_person', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->index();
            $table->foreign('campaign_id')->references('id')->on('campaign')->onDelete('cascade');
            $table->integer('person_id')->unsigned()->index();
            $table->string('activity')->nullable();
            $table->foreign('person_id')->references('id')->on('person')->onDelete('cascade');
            $table->primary(['campaign_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaign_person');
    }
}
