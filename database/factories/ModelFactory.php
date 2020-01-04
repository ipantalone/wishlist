<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => Hash::make('password'),
        'token' => Str::random(64)
    ];
});

$factory->define(Wishlist::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->words(3, true)
    ];
});

$factory->define(Product::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->words(1, true)
    ];
});