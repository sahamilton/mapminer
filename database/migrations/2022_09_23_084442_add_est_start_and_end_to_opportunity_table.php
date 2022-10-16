<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstStartAndEndToOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'opportunities', function (Blueprint $table) {
                $table->date('est_start')->nullable()->index();
                $table->date('est_end')->nullable()->index();
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
            'opportunities', function (Blueprint $table) {
                $table->dropColumn(['est_start', 'est_end']);
            }
        );
    }
}
