<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Food($faker));
    return [
        'title' => $faker->ingredient,
        'price' =>  rand (10,100),
        'discount' =>  rand (0,20),
        'discount_type' =>  rand (0,1),
    ];
});
