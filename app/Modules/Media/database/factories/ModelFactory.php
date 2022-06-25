<?php

use App\Modules\Media\Models\Media;
use Illuminate\Database\Eloquent\Factory;

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
$factory->define(Media::class, function (Faker\Generator $faker) {
    return [
        'link' => $faker->word,
        'name' => $faker->word
    ];
});
