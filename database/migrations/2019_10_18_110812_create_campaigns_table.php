<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $table->foreign('manager_id')->references('id')->on('persons')->onDelete('setnull');
                $table->foreign('created_by')->references('id')->on('persons')->onDelete('setnull');

               
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
