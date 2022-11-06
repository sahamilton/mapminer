<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchfilterTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searchfilter_training', function (Blueprint $table) {
            $table->unsignedInteger('training_id')->index('searchfilter_training_training_fk');
            $table->unsignedInteger('searchfilter_id')->index('searchfilter_training_searchfilter_fk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('searchfilter_training');
    }
}
