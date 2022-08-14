<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartEndTimesToActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'activities', function (Blueprint $table) {
                $table->time('starttime')->nullable();
                $table->time('endtime')->nullable();

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
        Schema::table(
            'activities', function (Blueprint $table) {
                $table->dropColumn(['starttime', 'endtime']);
            }
        );
    }
}
