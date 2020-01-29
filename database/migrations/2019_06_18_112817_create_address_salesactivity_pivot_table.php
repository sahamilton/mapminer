<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressSalesactivityPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'address_salesactivity', function (Blueprint $table) {
                $table->integer('address_id')->unsigned()->index();

                $table->integer('salesactivity_id')->unsigned()->index();

                $table->primary(['address_id', 'salesactivity_id']);
            }
        );
        Schema::table(
            'address_salesactivity', function (Blueprint $table) {
                $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');

                $table->foreign('salesactivity_id')->references('id')->on('salesactivity')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('address_salesactivity');
    }
}
