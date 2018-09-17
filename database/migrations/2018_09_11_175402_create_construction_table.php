<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('construction', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->string('title');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->decimal('lat',20,14)->nullable();
            $table->decimal('lng',20,14)->nullable();
            $table->string('valuation')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('construction');
    }
}
