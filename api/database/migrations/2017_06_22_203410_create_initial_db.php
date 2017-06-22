<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survivors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('age');
            $table->integer('gender');
//            $table->integer('location');
//            $table->integer('survivorItems');
            $table->boolean('isInfected')->default(false);
        });

        Schema::create('survivors_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('quantity');
            $table->integer('item');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });

        Schema::create('item_survivoritem', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('quantity');
            $table->integer('item');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('latitude');
            $table->integer('longitude');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });

        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('points');
            $table->integer('gender');
            $table->integer('location');
            $table->integer('survivorItems');

            $table->boolean('isInfected')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('survivors');
    }
}
