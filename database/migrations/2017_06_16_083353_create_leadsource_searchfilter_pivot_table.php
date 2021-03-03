<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeadsourceSearchfilterPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadsource_searchfilter', function (Blueprint $table) {
            $table->integer('leadsource_id')->unsigned()->index();
            $table->foreign('leadsource_id')->references('id')->on('leadsources')->onDelete('cascade');
            $table->integer('searchfilter_id')->unsigned()->index();
            $table->foreign('searchfilter_id')->references('id')->on('searchfilters')->onDelete('cascade');
            $table->primary(['leadsource_id', 'searchfilter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lead_searchfilter');
    }
}
