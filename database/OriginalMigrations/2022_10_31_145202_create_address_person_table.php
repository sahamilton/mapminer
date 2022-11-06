<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_person', function (Blueprint $table) {
            $table->unsignedInteger('address_id')->index();
            $table->unsignedInteger('person_id')->index();
            $table->integer('status_id')->nullable();
            $table->integer('ranking')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->primary(['address_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_person');
    }
}
