<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
            $table->string('firstname', 30);
            $table->string('lastname', 40);
            $table->float('lat', 10, 0)->nullable();
            $table->float('lng', 10, 0)->nullable();
            $table->unsignedInteger('user_id')->index();
            $table->string('phone', 50)->nullable();
            $table->string('address', 80)->nullable();
            $table->unsignedInteger('reports_to')->nullable()->index('persons_reports_to_fk');
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();
            $table->string('city', 30)->nullable();
            $table->string('state', 10)->nullable();
            $table->string('zip', 10)->nullable();
            $table->boolean('geostatus')->nullable();
            $table->date('active_from')->nullable();
            $table->date('hiredate')->nullable();
            $table->string('business_title')->nullable();
            $table->string('avatar', 60)->default('avatar.png');
            $table->string('country')->default('US');
            $table->point('position')->nullable();

            $table->fullText(['firstname', 'lastname'], 'fullName');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
