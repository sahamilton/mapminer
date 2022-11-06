<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_project', function (Blueprint $table) {
            $table->unsignedInteger('person_id')->index('person_project_person_id_foreign');
            $table->string('related_id', 60)->index('project_person_person_id_foreign');
            $table->string('type', 10);
            $table->string('status', 100)->nullable();
            $table->integer('ranking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_project');
    }
}
