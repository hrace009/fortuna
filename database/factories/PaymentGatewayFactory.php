<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\PaymentGateway;
use Faker\Generator as Faker;

$factory->define(PaymentGateway::class, function (Faker $faker) {
    return [
        'name' => 'PagHiper',
        'slug' => 'paghiper',
        'enabled' => true,
    ];
});
