<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignServicelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_serviceline', function (Blueprint $table) {
            $table->unsignedInteger('campaign_id')->index();
            $table->unsignedInteger('serviceline_id')->index();

            $table->primary(['campaign_id', 'serviceline_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_serviceline');
    }
}
