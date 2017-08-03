<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectcompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectcompany', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('firm')->nullable();
            $table->string('contact')->nullable();
            $table->string('title')->nullable();
            $table->string('addr1')->nullable();
            $table->string('addr2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('county')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('company_lat',20,14)->nullable();
            $table->decimal('company_lng',20,14)->nullable();
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
        Schema::dropIfExists('projectcompany');
    }
}
