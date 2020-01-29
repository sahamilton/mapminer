<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'campaign_documents', function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('campaign_id')->unsigned()->index();
                $table->string('link');
                $table->string('title');
                $table->text('description');
                $table->enum('type', ['pdf', 'excel', 'powerpoint', 'url']);
                $table->timestamps();
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
        Schema::dropIfExists('campaign_documents');
    }
}
