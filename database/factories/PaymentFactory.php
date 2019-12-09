<?php

use App\Models\GroupModel;
use App\Models\PaymentModel;
use App\Models\ServerModel;
use App\User;
use Faker\Generator as Faker;

$factory->define(PaymentModel::class, function (Faker $faker) {
	$types = ['Server','Setup Fee','Ip','Domain','Transfer Fee','Inboxe','Other'];
	$currencies = ['USD','EUR','GBP','MAD'];
    return [
        'type'=>$faker->randomElement($types),
        'group_id'=>GroupModel::all()->random()->id,
        'unitPrice'=>rand(1,300),
        'quantity'=>rand(1,20),
        'totalPrice'=>rand(1,6000),
        'currency'=>$faker->randomElement($currencies),
        'paymentDate'=>now(),
        'createDate'=>now(),
        'description'=>$faker->sentence,
        'created_by'=>User::where('role','root')->get()->random()->id,
    ];
});
