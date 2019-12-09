<?php

use App\Models\GroupModel;
use App\Models\RequestModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(RequestModel::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['New Server','Server Cancelation','Ip Range','Sponsor','Boxes','Other']),
        'subject' => $faker->sentence,
        'request'=>$faker->sentence,
        'priority' => $faker->randomElement(['Low','Medium','High']),
        'user_id' => User::all()->random()->id,
        'group_id' =>GroupModel::all()->random()->id,
        '_status'=>$faker->randomElement(['New','Inprocess','Solved','Other','NotFound']),
    ];
});
