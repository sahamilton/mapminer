<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->text('note');
            $table->string('type', 20)->default('location');
            $table->string('related_id', 60)->index('notes_location_id_index');
            $table->unsignedInteger('address_id')->nullable()->index('address_id');
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('activity')->nullable();
            $table->integer('contact_id')->nullable();
            $table->dateTime('activity_date')->nullable();
            $table->dateTime('followup_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
