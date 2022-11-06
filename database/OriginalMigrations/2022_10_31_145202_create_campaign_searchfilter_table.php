<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignSearchfilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_searchfilter', function (Blueprint $table) {
            $table->unsignedInteger('campaign_id')->index();
            $table->unsignedInteger('searchfilter_id')->index();

            $table->primary(['campaign_id', 'searchfilter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_searchfilter');
    }
}
