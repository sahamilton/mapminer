<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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

            $table->string('branch_id', 20)->index()->collation('utf8_general_ci');
            $table->integer('orders');
            $table->string('period');
        });

        Schema::table('address_branch', function (Blueprint $table) {
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
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
