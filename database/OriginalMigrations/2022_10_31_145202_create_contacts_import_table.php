<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_import', function (Blueprint $table) {
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
            $table->boolean('geostatus')->default(true);
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('lead_source_id')->nullable();
            $table->unsignedInteger('import_ref')->nullable();
            $table->timestamps();
            $table->point('position');
            $table->string('fullname', 255)->nullable();
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('email')->nullable();
            $table->string('contactphone', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts_import');
    }
}
