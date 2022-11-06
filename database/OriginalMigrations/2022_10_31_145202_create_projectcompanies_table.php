<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProjectcompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectcompanies', function (Blueprint $table) {
            $table->string('id', 60)->primary();
            $table->string('firm', 255)->nullable();
            $table->string('addr1', 255)->nullable();
            $table->string('addr2', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->string('county', 255)->nullable();
            $table->string('phone', 255)->nullable();

            $table->index([DB::raw("state(191)")], 'state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projectcompanies');
    }
}
