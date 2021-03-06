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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Test::class, function (Faker\Generator $faker) {
    return [
        // 'course_id' => $faker->randomDigit,
        'date' => $faker->date,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Course::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
        'ch' => $faker->randomElement([30, 60, 90]),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Professor::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Request::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->word),
        'ch' => $faker->randomElement([30, 60, 90]),
        'outline' => $faker->text,
    ];
});
