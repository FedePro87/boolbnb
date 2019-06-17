<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Apartment::class, function (Faker $faker) {
    return [
        'title' =>$faker->word(3),
        'description' =>$faker->sentence(50),
        'price' =>$faker->numberBetween(20, 100),
        'number_of_rooms' =>$faker->numberBetween(1 , 5),
        'bathrooms' =>$faker->numberBetween(1 , 3),
        'bedrooms' =>$faker->numberBetween(1 , 5),
        'square_meters' =>$faker->numberBetween(20 , 100),
        'address' =>$faker->streetAddress,
        'image' =>$faker->imageUrl(640, 480, 'city')
    ];
});
