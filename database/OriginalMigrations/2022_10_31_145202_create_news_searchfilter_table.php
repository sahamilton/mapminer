<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsSearchfilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_searchfilter', function (Blueprint $table) {
            $table->unsignedInteger('news_id')->index();
            $table->unsignedInteger('searchfilter_id')->index();

            $table->primary(['news_id', 'searchfilter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_searchfilter');
    }
}
