<?php

use App\Models\StatusModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(StatusModel::class, function (Faker $faker) {
    return [
        'status'=>$faker->randomElement(['Pending','Installing','In-Prod','In-Prod with Issue','To Return','Returned','Canceled by Provider','Suspended']),
        'explication'=>$faker->sentence,
        'user_id'=> User::all()->random()->id,
    ];
});
