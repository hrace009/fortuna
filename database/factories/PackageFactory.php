<?php

use Faker\Generator as Faker;

$factory->define(App\PaymentPackage::class, function (Faker $faker) {
    for ($i = 1; $i < 30; $i++) {
        return [
            'price' => $i + 5,
            'cash'  => ($i + 5) * 100,
        ];
    }
});
