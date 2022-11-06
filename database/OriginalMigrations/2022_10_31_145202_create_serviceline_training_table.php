<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicelineTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceline_training', function (Blueprint $table) {
            $table->unsignedInteger('serviceline_id')->index();
            $table->unsignedInteger('training_id')->index();

            $table->primary(['serviceline_id', 'training_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serviceline_training');
    }
}
