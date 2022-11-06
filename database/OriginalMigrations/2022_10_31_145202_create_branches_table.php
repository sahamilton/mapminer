<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('id', 20)->unique('id');
            $table->text('branchname');
            $table->string('oracle_location')->nullable()->unique('oracle_location');
            $table->unsignedInteger('person_id')->nullable();
            $table->text('street');
            $table->text('address2')->nullable();
            $table->text('city');
            $table->text('state');
            $table->text('zip');
            $table->text('phone')->nullable();
            $table->text('fax')->nullable();
            $table->double('lat', 15, 8);
            $table->double('lng', 15, 8);
            $table->integer('radius')->nullable()->default(25);
            $table->string('country')->default('USA');
            $table->softDeletes();
            $table->integer('region_id');
            $table->point('position')->nullable();

            $table->index(['lat', 'lng'], 'latlng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
