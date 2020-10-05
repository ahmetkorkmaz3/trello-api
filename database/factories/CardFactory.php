<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Card;
use Faker\Generator as Faker;
use App\Models\Column;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'column_id' => function() {
            return Column::all()->random();
        }
    ];
});
