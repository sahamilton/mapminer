<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report')->index();
            $table->text('description');
            $table->text('details')->nullable();
            $table->string('job')->index();
            $table->string('export')->nullable();
            $table->string('object')->nullable();
            $table->string('period_from')->nullable();
            $table->string('period_to')->nullable();
            $table->timestamps();
            $table->boolean('period')->default(true);
            $table->boolean('public')->default(false);
            $table->string('mail')->nullable();
            $table->boolean('campaign')->default(false);
            $table->string('filename')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
