<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectsource', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source', 255);
            $table->string('reference', 255)->nullable();
            $table->text('description')->nullable();
            $table->date('datefrom');
            $table->date('dateto');
            $table->string('status', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projectsource');
    }
}
