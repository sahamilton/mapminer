<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_branch', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('address_id')->index('address_branch_address_id_foreign');
            $table->string('branch_id', 20)->nullable()->index('address_branch_branch_id_fk');
            $table->unsignedInteger('status_id')->nullable();
            $table->boolean('top50')->nullable();
            $table->integer('rating')->nullable();
            $table->unsignedInteger('person_id')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedInteger('orders_id')->nullable()->index('orders_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->date('last_activity')->nullable()->index('last_activity');

            $table->index(['address_id', 'last_activity'], 'address_last_activity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_branch');
    }
}
