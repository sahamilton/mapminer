<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('addressable_id')->index();           
            $table->integer('company_id')->unsigned()->nullable();            
            $table->decimal('lat',20,14)->nullable()->index();                 
            $table->decimal('lng',20,14)->nullable()->index();                              
            $table->text('businessname');                 
            $table->string('street')->index();       
            $table->string('address2')->nullable();          
            $table->string('city')->index();         
            $table->string('state')->index();                       
            $table->string('zip')->index();              
            $table->string('phone')->nullable();          
            $table->string('contact')->nullable();          
            $table->integer('segment')->nullable();          
            $table->integer('businesstype')->nullable();         
            $table->boolean('geostatus')->default(1);            
            $table->string('addressable_type')->default('location');             
            $table->integer('lead_source_id')->unsigned()->nullable()->index();  
            $table->integer('vertical')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
            $table->foreign('lead_source_id')
                ->references('id')->on('leadsources')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
        });
    }
}
