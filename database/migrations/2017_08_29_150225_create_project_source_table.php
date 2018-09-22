<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSourceTable extends Migration
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
            $table->string('source');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->date('datefrom');
            $table->date('dateto');
            $table->string('status');
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
        Schema::table('projectsource', function (Blueprint $table) {
            $dropTable('projectsource');
        });
    }
}
