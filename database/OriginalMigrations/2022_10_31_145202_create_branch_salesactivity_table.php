<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchSalesactivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_salesactivity', function (Blueprint $table) {
            $table->string('branch_id', 20)->index();
            $table->unsignedInteger('salesactivity_id')->index();

            $table->primary(['branch_id', 'salesactivity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_salesactivity');
    }
}
