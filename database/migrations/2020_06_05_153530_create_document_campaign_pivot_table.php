<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCampaignPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_document', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()->index();
            
            $table->integer('campaign_id')->unsigned()->index();
        });    
        Schema::table('campaign_document', function (Blueprint $table) {
           
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
          
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->primary(['document_id', 'campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
    {
        Schema::dropIfExists('campaign_document');
    }
}
