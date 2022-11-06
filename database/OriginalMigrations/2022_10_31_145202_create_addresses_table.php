<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('addressable_id')->nullable()->index();
            $table->unsignedInteger('company_id')->nullable()->index('addresses_company_id_foreign');
            $table->decimal('lat', 20, 14)->nullable()->index();
            $table->decimal('lng', 20, 14)->nullable()->index();
            $table->text('businessname');
            $table->string('street')->index();
            $table->string('address2')->nullable();
            $table->string('city')->index();
            $table->string('state')->index();
            $table->string('zip')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('contact')->nullable();
            $table->text('description')->nullable();
            $table->integer('segment')->nullable();
            $table->integer('businesstype')->nullable();
            $table->unsignedInteger('customer_id')->nullable()->index('customer_id');
            $table->boolean('geostatus')->default(true);
            $table->string('addressable_type')->default('location');
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->unsignedInteger('lead_source_id')->nullable()->index();
            $table->unsignedInteger('import_ref')->nullable()->index('addresses_import_ref_fk');
            $table->unsignedInteger('vertical')->nullable();
            $table->timestamps();
            $table->point('position');
            $table->string('duns', 10)->nullable()->unique();
            $table->string('naic', 6)->nullable()->index();
            $table->string('country')->default('USA');
            $table->boolean('isCustomer')->nullable()->index();
            $table->integer('industry_id')->nullable();

            $table->index(['company_id'], 'company_id');
            $table->fullText(['businessname', 'street', 'city', 'zip', 'state'], 'fullAddress');
            $table->spatialIndex(['position'], 'position');
            $table->unique(['addressable_type', 'addressable_id'], 'RelatedExtra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
