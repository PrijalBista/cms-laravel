<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feed;
use Faker\Generator as Faker;

$factory->define(Feed::class, function (Faker $faker) {
    return [
        'title' => $faker->realText($maxNbChars=100),
        'content' => $faker->realText($maxNbChars=300)
    ];
});
