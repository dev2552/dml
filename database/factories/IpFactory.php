<?php

use App\Models\IpModel;
use App\Models\ProviderModel;
use App\Models\ServerModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(IpModel::class, function (Faker $faker) {
    return [
        'server_id'=> ServerModel::all()->random()->id,
        'provider_id'=>ProviderModel::all()->random()->id,
        'ip'=>$faker->ipv4,
        'price'=>$faker->randomNumber(2),
        'currency'=>$faker->randomElement(['USD','EUR','GBP','MAD']),
        'type'=>$faker->randomElement(['IPV4','IPV6','FailOver','Subnet']),
        'enable'=>$faker->boolean,
        'ipCountry'=>$faker->country,
        'ipCountryCode'=>$faker->countryCode,
        'status'=>$faker->randomElement(['New','In-Prod','Blocked','Canceled']),
        'gateway'=>$faker->ipv4,
        'created_by'=>User::where('role','root')->get()->random()->id,
    ];
});
