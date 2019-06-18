<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;


use Faker\Generator as Faker;

$factory->define(App\Sponsorship::class, function (Faker $faker) {
    return [
        'type'=>$faker->word(1)
    ];
});


