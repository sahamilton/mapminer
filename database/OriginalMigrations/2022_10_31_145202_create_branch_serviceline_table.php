<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchServicelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_serviceline', function (Blueprint $table) {
            $table->string('branch_id', 20)->index('branch_serviceline_branch_id_fk');
            $table->unsignedInteger('serviceline_id')->index('branch_serviceline_foreign_key_serviceline');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['branch_id', 'serviceline_id'], 'branch_serviceline_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_serviceline');
    }
}
