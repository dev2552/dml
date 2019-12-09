<?php

use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\ProviderModel;
use App\Models\ServerModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(ServerModel::class, function (Faker $faker) {
    return [
        'group_id'=>GroupModel::all()->random()->id,
        'provider_id'=>ProviderModel::all()->random()->id,
        'name'=>$faker->name,
        'orderNumber'=>$faker->randomDigitNotNull,
        'mainIp'=> $faker->ipv4,
        'domain_id'=>DomainModel::all()->random()->id,
        'sshUserDefault'=>$faker->word,
        'sshType'=>'Normal',
        'sshPasswordDefault'=>$faker->password,
        'price'=>$faker->randomNumber(2),
        'currency'=>$faker->randomElement(['USD','EUR','GBP','MAD']),
        'dateOrder'=>now(),
        'datePurchase'=>now(),
        'dateExpiration'=>now()->addMonth(),
        'os'=>'Linux',
        'cpu'=>'2 vCPU core',
        'ram'=>'2 GB',
        'hdd'=>'60 GB',
        'description'=>$faker->sentence,
        'note'=>$faker->sentence,
        'type'=>$faker->randomElement(['Production','Developpement','RDP','Administration','Other']),
        'dateDelivred'=>now(),
        'mainIpCountry'=>$faker->country,
        'mainIpCountryCode'=>$faker->countryCode,
        'created_by' => User::where('role','root')->get()->random()->id,
    ];
});
