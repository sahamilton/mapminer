<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadimport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('companyname', 255);
            $table->string('customer_number', 20);
            $table->string('businessname', 255);
            $table->string('address', 255);
            $table->string('city', 255);
            $table->string('state', 255);
            $table->string('zip', 255);
            $table->string('contact', 255);
            $table->string('contacttitle', 20)->nullable();
            $table->string('contactemail', 20)->nullable();
            $table->string('phone', 255);
            $table->text('description')->nullable();
            $table->date('datefrom');
            $table->date('dateto');
            $table->decimal('lat', 12, 7)->nullable();
            $table->decimal('lng', 12, 7)->nullable();
            $table->unsignedInteger('lead_source_id')->index('leads_lead_source_id_index');
            $table->unsignedInteger('pid')->nullable();
            $table->string('employee_id', 20);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leadimport');
    }
}
