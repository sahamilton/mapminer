<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivityFieldToNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string('activity')->nullable();
            $table->integer('contact_id')->nullable();
            $table->datetime('activity_date')->nullable();
            $table->datetime('followup_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Notes', function (Blueprint $table) {
            $table->dropColumn(['activity','contact_id','activity_date','followup_date']);
        });
    }
}
