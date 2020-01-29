<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'report_distribution', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('report_id')->index()->unsigned();
                $table->integer('user_id')->index()->unsigned();
                $table->enum('type', ['to', 'cc', 'bcc'])->default('to');
                $table->timestamps();
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
        Schema::dropIfExists('report_distribution');
    }
}
