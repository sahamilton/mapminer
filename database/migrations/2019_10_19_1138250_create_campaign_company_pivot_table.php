<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCampaignCompanyPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'campaign_company', function (Blueprint $table) {
                $table->integer('campaign_id')->unsigned()->index();

                $table->integer('company_id')->unsigned()->index();

                $table->primary(['campaign_id', 'company_id']);
            }
        );
        Schema::table(
            'campaign_company', function (Blueprint $table) {
                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::drop('campaign_company');
    }
}
