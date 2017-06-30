<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Survivor::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'age' => rand(10, 20),
        'gender' => 'M'
    ];
});

$factory->define(App\SurvivorItem::class, function (Faker\Generator $faker) {
    return [
        'quantity' => 1,
        'survivor_id' => function () {
            return factory(App\Survivor::class)->create()->id;
        },
        'item_id' => function () {
            return factory(App\Item::class)->create()->id;
        }
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name' => str_random(5),
        'points' => rand(1,4)
    ];
});
