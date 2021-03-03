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
        Schema::create(
            'contacts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('firstname');
                $table->string('lastname');
                $table->string('title')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->integer('location_id')->index()->unsigned();
                $table->integer('user_id')->nullable()->index()->unsigned();
                $table->text('comments');
                $table->foreign('location_id')
                    ->references('id')->on('locations')
                    ->onDelete('cascade');
                $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('setnull');
                $table->timestamps();
            }
        );
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
