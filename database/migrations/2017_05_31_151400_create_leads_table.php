<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('companyname');
            $table->string('businessname');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('contact');
            $table->string('phone');
            $table->text('description')->nullable();
            $table->date('datefrom');
            $table->date('dateto');
            $table->decimal('lat', 12, 7)->nullable();
            $table->decimal('lng', 12, 7)->nullable();
            $table->integer('lead_source_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->index('lead_source_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
