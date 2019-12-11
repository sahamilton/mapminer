<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTop50ToTop25 extends Migration
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
                $table->renameColumn('Top50', 'Top25');
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
                $table->renameColumn('Top25', 'Top50');
            }
        );
    }
}
