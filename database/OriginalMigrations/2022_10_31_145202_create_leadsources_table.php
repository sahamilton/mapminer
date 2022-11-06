<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadsources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source', 255);
            $table->string('type', 10)->nullable();
            $table->text('description');
            $table->string('reference', 255)->nullable();
            $table->date('datefrom');
            $table->date('dateto');
            $table->string('filename', 255)->nullable();
            $table->integer('user_id');
            $table->integer('leadstatus');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leadsources');
    }
}
