<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebleadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webleads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('address_id')->unique('related_address');
            $table->text('jobs')->nullable();
            $table->string('rating', 10)->nullable();
            $table->string('time_frame', 100);
            $table->boolean('multiple')->nullable();
            $table->string('campaign_id', 200)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webleads');
    }
}
