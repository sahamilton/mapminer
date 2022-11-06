<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_person', function (Blueprint $table) {
            $table->string('branch_id', 20)->index('branch_person_branch_id_fk');
            $table->unsignedInteger('person_id')->index('branch_person_person_id_foreign');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedInteger('role_id')->index('branch_person_role_id_fk');

            $table->primary(['person_id', 'branch_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_person');
    }
}
