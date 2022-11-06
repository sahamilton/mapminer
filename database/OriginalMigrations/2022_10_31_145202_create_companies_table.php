<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('companyname', 255)->fulltext('companyname');
            $table->unsignedInteger('accounttypes_id')->nullable()->index('accounts_accounttypes_id_index');
            $table->unsignedInteger('person_id')->nullable()->index('companies_person_person_id_fk');
            $table->integer('vertical')->nullable()->index();
            $table->integer('location_count')->nullable();
            $table->string('customer_id', 60)->nullable()->unique('customer_id');
            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();

            $table->unique(['customer_id'], 'customer_id_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
