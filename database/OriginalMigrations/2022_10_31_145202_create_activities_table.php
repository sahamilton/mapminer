<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->date('activity_date')->index('activity_date');
            $table->date('followup_date')->nullable();
            $table->unsignedInteger('activitytype_id')->nullable()->index('activity_type_id_fk');
            $table->unsignedInteger('address_id')->index();
            $table->string('branch_id', 20)->nullable()->index('activity_branch_id_index');
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->unsignedInteger('relatedActivity')->nullable();
            $table->timestamps();
            $table->boolean('completed')->nullable()->index('completed');
            $table->unsignedInteger('address_branch_id')->nullable()->index('address_branch_id');
            $table->time('starttime')->nullable();
            $table->time('endtime')->nullable();

            $table->index(['branch_id', 'activity_date'], 'activity_date_branch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
