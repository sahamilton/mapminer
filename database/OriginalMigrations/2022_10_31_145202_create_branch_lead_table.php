<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_lead', function (Blueprint $table) {
            $table->string('branch_id', 20)->index('branch_lead_branch_fk');
            $table->unsignedInteger('address_id')->index('branch_lead_lead_fk');
            $table->timestamps();
            $table->unsignedInteger('status_id');
            $table->integer('rating')->nullable();
            $table->text('comments')->nullable();

            $table->unique(['branch_id', 'address_id'], 'UniqueLead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_lead');
    }
}
