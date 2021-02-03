<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'category_code' => Str::random(5),
        'category_name' => $faker->word,
        'created_at' => now(),
    ];
});
