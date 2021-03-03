php artisan<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSourcesTable extends Migration
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
            $table->string('source');
            $table->text('description');
            $table->string('reference')->nullable();
            $table->date('datefrom');
            $table->date('dateto');
            $table->string('filename')->nullable();
            $table->integer('user_id');
            $table->integer('leadstatus');
            $table->timestamps();
            $table->softDeletes();
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
