<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressCampaignPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'address_campaign', function (Blueprint $table) {
                $table->integer('address_id')->unsigned()->index();

                $table->integer('campaign_id')->unsigned()->index();
            }
        );

        Schema::table(
            'address_campaign', function (Blueprint $table) {
                $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');

                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
                $table->primary(['address_id', 'campaign_id']);
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
        Schema::dropIfExists('address_campaign');
    }
}
