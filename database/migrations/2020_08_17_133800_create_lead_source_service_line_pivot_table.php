<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSourceServiceLinePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'lead_source_serviceline', function (Blueprint $table) {
                $table->integer('lead_source_id')->unsigned()->index();
                $table->integer('serviceline_id')->unsigned()->index();

            }
        );
        Schema::table(
            'lead_source_serviceline', function (Blueprint $table) {
           
                $table->foreign('lead_source_id')->references('id')->on('leadsources')->onDelete('cascade');
              
                $table->foreign('serviceline_id')->references('id')->on('servicelines')->onDelete('cascade');
                $table->primary(['serviceline_id', 'lead_source_id']);
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
        Schema::dropIfExists('lead_source_serviceline');
    }
}
