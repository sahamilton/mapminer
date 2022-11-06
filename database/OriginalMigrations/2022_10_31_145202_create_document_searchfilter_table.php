<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSearchfilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_searchfilter', function (Blueprint $table) {
            $table->unsignedInteger('document_id')->index();
            $table->unsignedInteger('searchfilter_id')->index();

            $table->primary(['document_id', 'searchfilter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_searchfilter');
    }
}
