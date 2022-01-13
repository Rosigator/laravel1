<?php

use Faker\Generator as Faker;
use App\Profession as Profession;

$factory->define(Profession::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->sentence(3),
    ];
});
