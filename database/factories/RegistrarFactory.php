<?php

use App\Models\RegistrarModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(RegistrarModel::class, function (Faker $faker) {
    return [
        'name'=>$faker->safeEmailDomain,
        'company'=>$faker->domainName,
        'email'=>$faker->companyEmail,
        'password'=>$faker->password,
        'customerid'=>$faker->word,
        'key'=>$faker->randomDigit,
        'secret'=>$faker->password,
        'website'=>$faker->url,
        'phone'=>$faker->phoneNumber,
        'address'=>$faker->streetAddress,
        'description'=>$faker->sentence,
        'enable'=>$faker->boolean,
        'created_by'=> User::where('role','root')->get()->random()->id,
    ];
});
