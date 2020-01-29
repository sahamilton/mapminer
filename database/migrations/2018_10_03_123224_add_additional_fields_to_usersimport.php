<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToUsersimport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usersimport', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->integer('person_id')->unsigned()->nullable();
            $table->integer('reports_to')->unsigned()->nullable();
            $table->string('manager')->nullable();
            $table->string('industry')->nullable();
            $table->string('branches')->nullable();
            $table->string('business_title')->nullable();
            $table->date('hiredate')->nullable();
            $table->string('mgr_emp_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usersimport', function (Blueprint $table) {
            $table->dropColumn(['address', 'city', 'state', 'zip', 'person_id', 'reports_to', 'manager', 'industry', 'branches', 'business_title', 'hiredate', 'mgr_emp_id']);
        });
    }
}
