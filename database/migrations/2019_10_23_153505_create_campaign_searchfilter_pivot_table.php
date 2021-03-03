<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCampaignSearchfilterPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'campaign_searchfilter', function (Blueprint $table) {
                $table->integer('campaign_id')->unsigned()->index();
                $table->integer('searchfilter_id')->unsigned()->index();
                $table->primary(['campaign_id', 'searchfilter_id']);
            }
        );
        Schema::table(
            'campaign_searchfilter', function (Blueprint $table) {
                $table->foreign('searchfilter_id')->references('id')->on('searchfilters')->onDelete('cascade');
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
        Schema::drop('campaign_searchfilter');
    }
}
