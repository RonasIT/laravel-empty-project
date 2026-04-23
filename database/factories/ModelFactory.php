<?php

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
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

/** @var Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = Hash::make('secret'),
        'remember_token' => Str::random(10),
        'role_id' => Role::USER,
    ];
});

$factory->define(Role::class, function () {
    return [
        'name' => 'user',
    ];
});
$factory->define(Setting::class, function (Generator $faker) {
    return [
        'key' => $faker->word,
        'value' => $faker->word,
    ];
});
