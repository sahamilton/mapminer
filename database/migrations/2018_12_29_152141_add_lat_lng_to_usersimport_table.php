<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatLngToUsersimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usersimport', function (Blueprint $table) {
            $table->decimal('lat',20,14)->nullable();
            $table->decimal('lng',20,14)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usersimport', function (Blueprint $table) {
            $table->dropColumn(['lat','lng']);
        });
    }
}
