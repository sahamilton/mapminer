<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchSalesactivityPivotTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'branch_salesactivity', function (Blueprint $table) {
                $table->string('branch_id', 20)->index();
               
                $table->integer('salesactivity_id')->unsigned()->index();

                $table->primary(['branch_id', 'salesactivity_id']);
            }
        );
        Schema::table(
            'branch_salesactivity', function (Blueprint $table) {
               
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
               
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
        Schema::drop('branch_salesactivity');
    }
}
