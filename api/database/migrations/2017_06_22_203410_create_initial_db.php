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
            $table->boolean('is_infected')->default(false);
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('latitude')->default('0');
            $table->string('longitude')->default('0');
            $table->timestamps();

            $table->integer('survivor_id')->unsigned();
            $table->foreign('survivor_id')->references('id')->on('survivors')->onDelete('cascade');
        });

        Schema::create('votes_of_infections', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('survivor_id')->unsigned();
            $table->foreign('survivor_id')->references('id')->on('survivors')->onDelete('cascade');

            $table->integer('infected_survivor_id')->unsigned();
            $table->foreign('infected_survivor_id')->references('id')->on('survivors')->onDelete('cascade');
        });

        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('points');
            $table->timestamps();
        });

        Schema::create('item_survivoritem', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('quantity');
            $table->timestamps();

            $table->integer('survivor_id')->unsigned();
            $table->foreign('survivor_id')->references('id')->on('survivors')->onDelete('cascade');

            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::drop('locations');
        Schema::drop('votes_of_infection');
        Schema::drop('item_survivoritem');
        Schema::drop('items');
    }
}
