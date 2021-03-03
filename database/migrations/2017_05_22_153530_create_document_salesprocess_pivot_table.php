<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentSalesprocessPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_salesprocess', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()->index();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->integer('salesprocess_id')->unsigned()->index();
            $table->foreign('salesprocess_id')->references('id')->on('salesprocess')->onDelete('cascade');
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
        Schema::drop('document_salesprocess');
    }
}
