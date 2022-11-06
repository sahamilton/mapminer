<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityProcessVerticalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_process_vertical', function (Blueprint $table) {
            $table->unsignedInteger('activity_id')->index();
            $table->unsignedInteger('salesprocess_id')->index();
            $table->unsignedInteger('vertical_id')->index();

            $table->primary(['activity_id', 'vertical_id', 'salesprocess_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_process_vertical');
    }
}
