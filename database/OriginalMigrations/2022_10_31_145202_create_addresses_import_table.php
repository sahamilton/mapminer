<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses_import', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->decimal('lat', 20, 14)->nullable();
            $table->decimal('lng', 20, 14)->nullable();
            $table->text('businessname');
            $table->string('street');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact')->nullable()->unique('contact');
            $table->text('description')->nullable();
            $table->integer('segment')->nullable();
            $table->integer('businesstype')->nullable();
            $table->boolean('geostatus')->default(true);
            $table->string('addressable_type')->default('location');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('lead_source_id')->nullable();
            $table->unsignedInteger('import_ref')->nullable();
            $table->unsignedInteger('vertical')->nullable();
            $table->timestamps();
            $table->point('position');
            $table->string('fullname', 255)->nullable();
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('email')->nullable();
            $table->string('contactphone', 255)->nullable();
            $table->string('duns', 10)->nullable()->unique('duns');
            $table->string('naic', 6)->nullable();
            $table->string('branch_id')->nullable();
            $table->string('customer_id', 20)->nullable();
            $table->string('country', 4)->default('US');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses_import');
    }
}
