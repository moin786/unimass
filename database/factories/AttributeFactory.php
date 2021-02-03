<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attribute;
use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Attribute::class, function (Faker $faker) {
    return [
        'attribute_name' => Str::upper($faker->word),
        'attribute_type' => Str::random(5),
    ];
});
