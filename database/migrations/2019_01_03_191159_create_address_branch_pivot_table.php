<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressBranchPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_branch', function (Blueprint $table) {
            $table->integer('address_id')->unsigned()->index();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->string('branch_id',20)->index();
            $table->integer('orders');
            $table->string('period');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->primary(['address_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('address_branch');
    }
}
