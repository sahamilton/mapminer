<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id')->index();
            $table->integer('branch_id')->index();
            $table->string('client_ref')->nullable();
            $table->boolean('closed')->default(0);
            $table->integer('value')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('requirements')->nullable();
            $table->boolean('top50')->nullable();
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
        Schema::dropIfExists('opportunities');
    }
}
