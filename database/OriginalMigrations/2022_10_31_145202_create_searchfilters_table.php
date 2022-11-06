<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchfiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searchfilters', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->integer('parent_id')->nullable()->index();
            $table->integer('lft')->nullable()->index();
            $table->integer('rgt')->nullable()->index();
            $table->integer('depth')->nullable();
            $table->string('filter', 255);
            $table->enum('type', ['single', 'multiple', 'group'])->nullable();
            $table->enum('searchtable', ['companies', 'locations'])->nullable();
            $table->enum('searchcolumn', ['business', 'vertical', 'segment', 'businesstype'])->nullable();
            $table->boolean('canbenull')->default(false);
            $table->tinyInteger('inactive')->default(0);
            $table->string('color', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('searchfilters');
    }
}
