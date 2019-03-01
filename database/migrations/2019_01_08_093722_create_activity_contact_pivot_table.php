<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityContactPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_contact', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('activity_id')->unsigned()->index();
            
            $table->integer('contact_id')->unsigned()->index();
            
            $table->timestamps();
        });

        Schema::table('activity_contact', function (Blueprint $table) {

            $table->foreign('activity_id')
            ->references('id')
            ->on('activities')
            ->onDelete('cascade');
            $table->foreign('contact_id')
            ->references('id')
            ->on('contacts')
            ->onDelete('cascade');
            $table->unique(['activity_id', 'contact_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activity_contact');
    }
}
