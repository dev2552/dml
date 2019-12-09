<?php

use App\Models\GroupModel;
use Faker\Generator as Faker;

$factory->define(GroupModel::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement(['G1','G2','G3','G4','G5','GP']),
        'created_by' => 1,
    ];
});
