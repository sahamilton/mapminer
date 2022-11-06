<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('address_id')->index();
            $table->string('branch_id', 20)->nullable()->index();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('address_branch_id')->index('address_branch_opportunity_id_fk');
            $table->string('client_ref')->nullable();
            $table->boolean('closed')->default(false);
            $table->boolean('Top25')->nullable();
            $table->integer('value')->nullable();
            $table->integer('requirements')->nullable();
            $table->integer('duration')->nullable();
            $table->text('description')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->date('expected_close')->nullable();
            $table->date('actual_close')->nullable();
            $table->boolean('csp')->nullable()->default(false)->index();
            $table->date('est_start')->nullable()->index();
            $table->date('est_end')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
}
