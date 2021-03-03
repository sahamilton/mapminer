<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'campaigns', function (Blueprint $table) {
                $table->enum('status', ['planned', 'launched', 'ended'])->default('planned');
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
            'campaigns', function (Blueprint $table) {
                $table->dropColumn('status');
            }
        );
    }
}
