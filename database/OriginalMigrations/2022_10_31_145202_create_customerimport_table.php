<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customerimport', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('businessname', 255)->fulltext('businessname');
            $table->unsignedInteger('accounttypes_id')->index('accounts_accounttypes_id_index');
            $table->string('customer_id', 60)->nullable();
            $table->decimal('lat', 20, 10)->nullable();
            $table->decimal('lng', 20, 10)->nullable();
            $table->string('accuracy', 5)->nullable();
            $table->string('street');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('phone')->nullable();
            $table->string('contact')->nullable();
            $table->string('orders', 20);
            $table->string('branch_id', 20)->nullable();
            $table->integer('address_id')->nullable();
            $table->integer('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customerimport');
    }
}
