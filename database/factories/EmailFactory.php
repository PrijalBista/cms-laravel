<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Email;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {
    return [
    	'name' => $faker->name,
    	'email' => $faker->email,
    	'subject' => $faker->realText($naxNbChars=100),
        'message' => $faker->realText($maxNbChars=300),
    ];
});
