<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GoldPackage;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(GoldPackage::class, function (Faker $faker) {
    return [
        'package_id' => Str::orderedUuid(),
        'package_name' => 'Pacote Gold',
        'package_price' => 10,
        'package_gold_amount' => 1000,
    ];
});
