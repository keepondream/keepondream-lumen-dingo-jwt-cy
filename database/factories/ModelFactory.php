<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
    $faker = \Faker\Factory::create('zh_CN');

    return [
        'mobile' => $faker->phoneNumber,
        'nick_name' => $faker->name,
        'is_black' => 2,
        'password' => (string)$faker->numberBetween(100000, 999999),
    ];
});

$factory->define(\App\Models\AdminUser::class, function (Faker\Generator $faker) {
    $faker = \Faker\Factory::create('zh_CN');

    return [
        'mobile' => $faker->phoneNumber,
        'nick_name' => $faker->name,
        'is_black' => 2,
        'password' => (string)$faker->numberBetween(100000, 999999),
    ];
});
