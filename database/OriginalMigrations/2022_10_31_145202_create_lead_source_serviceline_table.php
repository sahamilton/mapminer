<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadSourceServicelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_source_serviceline', function (Blueprint $table) {
            $table->unsignedInteger('lead_source_id')->index();
            $table->unsignedInteger('serviceline_id')->index();

            $table->primary(['serviceline_id', 'lead_source_id']);
        });
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
