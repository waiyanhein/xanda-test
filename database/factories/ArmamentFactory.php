<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Armament;
use App\Models\Spacecraft;

$factory->define(Armament::class, function (Faker $faker) {
    return [
        'spacecraft_id' => function () use ($faker) {
            return factory(Spacecraft::class)->create();
        },
        'title' => $faker->randomElement([ 'Turbo Laser', 'Ion Cannons', 'Tractor Beam' ]),
        'qty' => $faker->randomNumber(2),
    ];
});
