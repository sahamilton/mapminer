<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSalesprocessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_salesprocess', function (Blueprint $table) {
            $table->unsignedInteger('document_id')->index();
            $table->unsignedInteger('salesprocess_id')->index();

            $table->primary(['document_id', 'salesprocess_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_salesprocess');
    }
}
