<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Spacecraft;
use App\Models\Fleet;

$factory->define(Spacecraft::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([ 'Assassin', 'Devastator', 'Tank', 'Red Five' ]). ' ' .$faker->unique()->randomNumber(4),//column is unique
        'class' => $faker->randomElement(Spacecraft::CLASSES),
        'crew' => $faker->randomNumber(5),
        'image' => $faker->md5 . '.' . $faker->randomElement([ 'png', 'jpg', 'svg' ]),
        'value' => $faker->randomFloat(2, 1000, 5000),
        'status' => $faker->randomElement([ Spacecraft::STATUS_OPERATIONAL, Spacecraft::STATUS_DAMAGED ]),
        'fleet_id' => Fleet::first(),
    ];
});

$factory->state(Spacecraft::class, Spacecraft::STATUS_OPERATIONAL, function (Faker $faker) {
    return [
        'status' => Spacecraft::STATUS_OPERATIONAL,
    ];
});

$factory->state(Spacecraft::class, Spacecraft::STATUS_DAMAGED, function (Faker $faker) {
    return [
        'status' => Spacecraft::STATUS_DAMAGED,
    ];
});
