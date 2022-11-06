<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branchesimport', function (Blueprint $table) {
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->integer('id')->unique('id');
            $table->text('branchname');
            $table->unsignedInteger('person_id');
            $table->text('street');
            $table->text('address2')->nullable();
            $table->text('city');
            $table->text('state');
            $table->text('zip');
            $table->text('phone')->nullable();
            $table->text('fax');
            $table->integer('region_id');
            $table->double('lat', 15, 8);
            $table->double('lng', 15, 8);
            $table->integer('radius')->nullable()->default(25);
            $table->string('servicelines', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branchesimport');
    }
}
