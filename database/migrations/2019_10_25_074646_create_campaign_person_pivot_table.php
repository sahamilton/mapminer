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
            'campaign_person', function (Blueprint $table) {
                $table->integer('campaign_id')->unsigned()->index();
                $table->integer('person_id')->unsigned()->index();
                $table->primary(['campaign_id', 'person_id']);
            }
        );
        Schema::table(
            'campaign_person', function (Blueprint $table) {
                $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            
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
        Schema::drop('campaign_person');
    }
}
