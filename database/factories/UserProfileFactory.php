<?php

use Faker\Generator as Faker;
use App\UserProfile as UserProfile;
use Illuminate\Support\Facades\DB as DB;

$factory->define(UserProfile::class, function (Faker $faker) {

    $num_professions = DB::table('professions')->count();

    $profession_id = $num_professions === 0 ?
        null :
        $faker->numberBetween(1, $num_professions);

    return [
        'profession_id' => $profession_id,
        'twitter' => 'https://twitter.com/' . $faker->word,
        'bio' => $faker->text(300)
    ];
});
