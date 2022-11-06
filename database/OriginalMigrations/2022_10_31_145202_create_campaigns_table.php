<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('manager_id')->nullable()->index();
            $table->dateTime('datefrom');
            $table->dateTime('dateto');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->enum('status', ['planned', 'launched', 'ended', 'completed'])->default('planned');
            $table->string('type')->nullable();
            $table->unsignedInteger('campaignmanager_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
