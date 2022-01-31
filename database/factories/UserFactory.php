<?php

use Faker\Generator as Faker;
use App\User as User;
use Illuminate\Support\Facades\DB as DB;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $num_professions = DB::table('professions')->count();

    $profession_id = $num_professions === 0 ?
        null :
        $faker->numberBetween(1, $num_professions);

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'profession_id' => $profession_id,
        'remember_token' => str_random(10),
    ];
});
