<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 40)->unique('email');
            $table->string('password', 60);
            $table->string('confirmation_code', 60)->nullable();
            $table->string('remember_token', 60)->nullable();
            $table->boolean('confirmed')->default(false);
            $table->dateTime('lastlogin')->nullable();
            $table->dateTime('nonews')->nullable();
            $table->integer('mgrid')->nullable()->index();
            $table->string('employee_id', 20)->unique('employee_id');
            $table->string('api_token', 60)->nullable()->index('api_token');
            $table->timestamps();
            $table->softDeletes();
            $table->point('position')->nullable();

            $table->unique(['api_token']);
            $table->index(['employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
