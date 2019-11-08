<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNodeFieldsToHowtoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'howtofields', function (Blueprint $table) {
                 $table->integer('parent_id')->unsigned()->nullable()->index();
                 $table->integer('lft')->index()->nullable();
                 $table->integer('rgt')->index()->nullable();
                 $table->integer('depth')->index()->nullable();
                 $table->boolean('active')->default(1)->index();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'howtofields', function (Blueprint $table) {
                $table->dropColumns(['parent_id', 'lft', 'rgt', 'depth']);
            }
        );
    }
}
