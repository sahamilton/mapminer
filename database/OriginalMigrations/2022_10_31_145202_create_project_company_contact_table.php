<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectCompanyContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_company_contact', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('project_id')->index('ProjectID');
            $table->string('project_ref', 60)->index('project_id');
            $table->string('company_id', 60)->index('projectcompany_id');
            $table->string('type', 255);
            $table->string('contact_id', 60)->nullable()->index('contact_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_company_contact');
    }
}
