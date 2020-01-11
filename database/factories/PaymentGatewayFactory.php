<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Models\PaymentGateway;

$factory->define(PaymentGateway::class, function (Faker $faker) {
    return [
        'name' => 'PagHiper',
        'slug' => 'paghiper',
        'enabled' => true,
    ];
});
