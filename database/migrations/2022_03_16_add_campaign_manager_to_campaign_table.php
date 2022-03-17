<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampaignManagerToCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'campaigns', function (Blueprint $table) {
                $table->integer('campaignmanager_id')->unsigned()->nullable()->index();
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
        Schema::table(
            'campaigns', function (Blueprint $table) {
                $table->dropColumn('campaignmanager_id');
            }
        );
    }
}
