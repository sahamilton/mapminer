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
        Schema::create(
            'campaigns', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->text('description');
                $table->integer('manager_id')->unsigned()->nullable()->index();
                $table->dateTime('datefrom');
                $table->dateTime('dateto');
                $table->integer('created_by')->unsigned()->nullable()->index();
                $table->timestamps();
            }
        );
        Schema::table(
            'campaigns', function (Blueprint $table) {
                $table->foreign('manager_id')->references('id')->on('persons')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('campaigns');
    }
}
