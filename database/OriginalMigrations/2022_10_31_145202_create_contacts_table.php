<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname', 255)->nullable();
            $table->string('firstname', 60)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('contactphone', 255)->nullable();
            $table->unsignedInteger('location_id')->nullable()->index();
            $table->unsignedInteger('address_id')->nullable()->index('address_id');
            $table->unsignedInteger('user_id')->nullable()->index('user_id');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->boolean('primary')->nullable()->default(false);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
