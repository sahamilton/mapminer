<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsourceSearchfilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadsource_searchfilter', function (Blueprint $table) {
            $table->unsignedInteger('leadsource_id')->index();
            $table->unsignedInteger('searchfilter_id')->index();

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
        Schema::dropIfExists('leadsource_searchfilter');
    }
}
