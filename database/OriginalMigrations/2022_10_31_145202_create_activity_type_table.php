<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity');
            $table->string('slug', 20)->nullable();
            $table->integer('sequence')->nullable();
            $table->timestamps();
            $table->string('color')->nullable();
            $table->boolean('activty')->default(true);
            $table->text('definition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_type');
    }
}
