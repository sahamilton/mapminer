<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadcontactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadcontacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lead_id')->index('leadcontact_leads_fk');
            $table->integer('address_id')->nullable();
            $table->string('contact', 255);
            $table->string('contacttitle', 255)->nullable();
            $table->string('contactemail', 255)->nullable();
            $table->string('contactphone', 255)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('leadcontacts');
    }
}
