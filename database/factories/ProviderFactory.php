<?php

use App\Models\ProviderModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProviderModel::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'country' => $faker->country,
        'urlSite' => $faker->url,
        'cpanel' => $faker->url,
        'login' => $faker->userName,
        'password' => $faker->password,
        'email' => $faker->email,
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'status' => $faker->randomElement(['Active','Inactive','Other']),
        'type' => $faker->randomElement(['Principal','Reseller','Canceled']),
        'note' => $faker->sentence,
        'created_by'=>User::where('role','root')->get()->random()->id,
    ];
});
