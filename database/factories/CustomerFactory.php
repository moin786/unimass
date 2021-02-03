<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'full_name' => $faker->name,
        'address1' => $faker->address,
        'address2' => $faker->streetAddress,
        'city' => $faker->city,
        'post_code' => $faker->numberBetween(20000000,30000000),
        'state' => $faker->countryCode,
        'country' => $faker->country,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
    ];
});
