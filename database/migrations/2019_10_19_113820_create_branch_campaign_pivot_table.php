<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchCampaignPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'branch_campaign', function (Blueprint $table) {
                $table->string('branch_id', 20)->collation('utf8_general_ci')->index();

                $table->integer('campaign_id')->unsigned()->index();

                $table->primary(['branch_id', 'campaign_id']);
            }
        );
        Schema::table(
            'branch_campaign', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

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
        Schema::drop('branch_campaign');
    }
}
