<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignServicelinePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'campaign_serviceline', function (Blueprint $table) {
                $table->integer('campaign_id')->unsigned()->index();
                $table->integer('serviceline_id')->unsigned()->index();
                $table->primary(['campaign_id', 'serviceline_id']);
            }
        );
        Schema::table(
            'campaign_serviceline', function (Blueprint $table) {
                $table->foreign('serviceline_id')->references('id')->on('servicelines')->onDelete('cascade');
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
        Schema::drop('document_searchfilter');
    }
}
