<?php

use App\Models\ServerModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Models\SubDomainModel::class, function (Faker $faker) {
    return [
        'ip' => $faker->ipv4,

        'subdomain' => $faker->word.'.'.$faker->domainName,

        'domain' => $faker->domainName,

        'server_id' => ServerModel::all()->random()->id,

        'enable' => true,

    ];
});
