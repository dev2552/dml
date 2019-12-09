<?php

use App\OpenExchangeRates;
use Faker\Generator as Faker;

$factory->define(OpenExchangeRates::class, function (Faker $faker) {
    return [
        'MAD' => '1.5',
        'EUR' => '0.8',
        'GBP' => '1.2',
    ];
});
