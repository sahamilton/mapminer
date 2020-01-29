<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDUNStoAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'addresses', function (Blueprint $table) {
                $table->string('duns', 10)->nullable()->unique();
                $table->string('naic', 6)->nullable()->index();
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
            'addresses', function (Blueprint $table) {
                $table->dropColumn(['duns', 'naic']);
            }
        );
    }
}
