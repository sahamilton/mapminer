<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressPersonPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('address_person', function (Blueprint $table) {
            $table->integer('address_id')->unsigned()->index();
            $table->integer('person_id')->unsigned()->index();
            $table->integer('rating');
            $table->text('comments')->nullable();
            $table->timestamps();
        });

         Schema::table('address_person', function (Blueprint $table) {
            $table->foreign('address_id')
            ->references('id')
            ->on('addresses')
            ->onDelete('cascade');
            $table->foreign('person_id')
            ->references('id')
            ->on('persons')
            ->onDelete('cascade');
            $table->primary(['address_id', 'person_id']);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('address_person');
    }
}
