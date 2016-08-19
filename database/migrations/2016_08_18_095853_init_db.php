<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v1', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dimension');
            $table->string('hash')->index();
            $table->unsignedInteger('key');
            $table->timestamps();
        });

        Schema::create('v2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->string('dimension');
            $table->unsignedInteger('key');
            $table->string('hash')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('v1');
        Schema::drop('v2');
    }
}
