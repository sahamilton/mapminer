<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $table->enum('type', ['to','cc','bcc'])->default('to');
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
