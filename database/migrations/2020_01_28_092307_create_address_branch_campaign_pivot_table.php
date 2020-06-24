<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressBranchCampaignPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'address_branch_campaign', function (Blueprint $table) {
                $table->integer('address_id')->unsigned()->index();
               
                $table->integer('campaign_id')->unsigned()->index();
               
            }
        );

        Schema::table(
            'address_branch_campaign', function (Blueprint $table) {
               
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
