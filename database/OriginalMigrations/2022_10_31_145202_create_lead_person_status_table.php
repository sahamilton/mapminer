<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadPersonStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_person_status', function (Blueprint $table) {
            $table->unsignedInteger('related_id')->index('lead_person_status_lead_id_index');
            $table->unsignedInteger('person_id')->index();
            $table->unsignedInteger('status_id')->index();
            $table->integer('rating')->nullable();
            $table->timestamps();
            $table->string('type', 20);

            $table->primary(['related_id', 'person_id', 'status_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_person_status');
    }
}
