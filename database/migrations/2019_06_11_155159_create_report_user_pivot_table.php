<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'report_user', function (Blueprint $table) {
                $table->integer('report_id')->unsigned()->index();
                $table->integer('user_id')->unsigned()->index();
                $table->primary(['report_id', 'user_id']);
            }
        );

        Schema::table(
            'report_user', function (Blueprint $table) {
                
                $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
              
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
        Schema::drop('report_user');
    }
}
