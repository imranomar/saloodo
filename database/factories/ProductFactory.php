<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'price' =>  rand (10,100),
        'price' =>  rand (1,2),
        'discount' =>  rand (0,20),
    ];
});
