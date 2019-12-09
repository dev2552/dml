<?php

use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\RegistrarModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(DomainModel::class, function (Faker $faker) {
    $currencies = ['USD','EUR','GBP','MAD'];
    return [
        'registrar_id'=>RegistrarModel::all()->random()->id,
        'domain'=>$faker->safeEmailDomain,
        'group_id'=>GroupModel::all()->random()->id,
        'datePurchase'=>now(),
        'dateExpiration'=>now(),
        'status'=>'New',
        'description'=>$faker->sentence,
        'enable'=>$faker->boolean,
        'expired'=>$faker->boolean,
        'currency'=>$faker->randomElement($currencies),
        'price'=>rand(1,250),
        'created_by' => User::where('role','root')->get()->random()->id,
    ];
});
