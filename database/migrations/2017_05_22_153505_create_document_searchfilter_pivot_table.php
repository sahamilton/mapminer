<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentSearchfilterPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_searchfilter', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()->index();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->integer('searchfilter_id')->unsigned()->index();
            $table->foreign('searchfilter_id')->references('id')->on('searchfilters')->onDelete('cascade');
            $table->primary(['document_id', 'searchfilter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('document_searchfilter');
    }
}
