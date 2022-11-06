<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersimport', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 40)->nullable()->unique('username');
            $table->string('firstname', 30);
            $table->string('lastname', 30);
            $table->string('email', 40)->nullable()->unique('email');
            $table->string('employee_id', 20)->nullable()->unique('employee_id');
            $table->unsignedInteger('user_id')->nullable()->unique('user_id');
            $table->string('serviceline', 255)->nullable();
            $table->unsignedInteger('role_id');
            $table->timestamps();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->unsignedInteger('person_id')->nullable();
            $table->unsignedInteger('reports_to')->nullable();
            $table->string('manager')->nullable();
            $table->string('industry')->nullable();
            $table->string('branches')->nullable();
            $table->string('business_title')->nullable();
            $table->string('hiredate', 10)->nullable();
            $table->string('mgr_emp_id')->nullable();
            $table->string('fullname');
            $table->decimal('lat', 20, 14)->nullable();
            $table->decimal('lng', 20, 14)->nullable();
            $table->boolean('imported')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usersimport');
    }
}
