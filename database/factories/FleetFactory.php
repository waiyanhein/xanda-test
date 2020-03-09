<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Fleet;

$factory->define(Fleet::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name, //unique column,
    ];
});
