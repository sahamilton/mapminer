<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectcontactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectcontacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact', 255)->default('0');
            $table->string('company_id', 60)->index('projectcompany_id');
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->string('title', 255)->nullable();
            $table->string('contactphone', 20)->nullable();
            $table->string('contactemail', 100)->nullable();
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
        Schema::dropIfExists('projectcontacts');
    }
}
