<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Column;
use Faker\Generator as Faker;
use App\Models\Board;

$factory->define(Column::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'board_id' => function () {
            return Board::all()->random();
        }
    ];
});
