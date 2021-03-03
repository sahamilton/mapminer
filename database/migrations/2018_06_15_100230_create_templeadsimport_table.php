<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempleadsimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templeadsimport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Contact_Prefix')->nullable();
            $table->string('Contact_First_Name')->nullable();
            $table->string('Contact_Last_Name')->nullable();
            $table->string('Contact_Title')->nullable();
            $table->string('Age')->nullable();
            $table->string('Company_Name')->nullable();
            $table->string('Primary_Address 1')->nullable();
            $table->string('Primary_City')->nullable();
            $table->string('Primary_County')->nullable();
            $table->string('Primary_State')->nullable();
            $table->string('Primary_Zip')->nullable();
            $table->string('Primary_Zip_Extension')->nullable();
            $table->string('Primary_Country')->nullable();
            $table->string('Phone_Number')->nullable();
            $table->string('FAX_Number')->nullable();
            $table->string('Web_Address')->nullable();
            $table->string('Latitude')->nullable();
            $table->string('Longitude')->nullable();
            $table->string('Line_Of_Business')->nullable();
            $table->string('Owns/Rents')->nullable();
            $table->string('Facility_Size')->nullable();
            $table->string('Is_Importer')->nullable();
            $table->string('Is_Exporter')->nullable();
            $table->string('D-U-N-S_Number')->nullable();
            $table->string('Location_Type')->nullable();
            $table->string('Ultimate_Parent')->nullable();
            $table->string('Ultimate_Parent_D-U-N-S')->nullable();
            $table->string('Immediate_Parent')->nullable();
            $table->string('Immediate_Parent_D-U-N-S')->nullable();
            $table->string('EIN')->nullable();
            $table->string('Revenue')->nullable();
            $table->string('Primary_Industry')->nullable();
            $table->string('Primary_US_SIC_Code')->nullable();
            $table->string('All_US_SIC_Codes')->nullable();
            $table->string('Primary_US_NAICS_Code')->nullable();
            $table->string('All_NAICS_Codes')->nullable();
            $table->string('Postal_Delivery_Point')->nullable();
            $table->string('Branch')->nullable();
            $table->string('Market_Manager')->nullable();
            $table->string('sr_id')->nullable();

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
        Schema::dropIfExists('templeadsimport');
    }
}
