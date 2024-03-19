<?php

use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = Hash::make('secret'),
        'remember_token' => Str::random(10),
        'role_id' => Role::USER,
    ];
});

$factory->define(App\Models\Role::class, function () {
    return [
        'name' => 'user',
    ];
});
$factory->define(App\Models\Setting::class, function (Faker\Generator $faker) {
    return [
        'key' => $faker->word,
        'value' => $faker->word,
    ];
});
