<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCustomerToAddressTable extends Migration
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
                $table->boolean('isCustomer')->nullable()->default(null)->index();
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
                $table->dropColumn('isCustomer');
            }
        );
    }
}
