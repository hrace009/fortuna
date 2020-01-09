<?php

use App\Models\Payments;
use Faker\Generator as Faker;

$factory->define(Payments::class, function (Faker $faker) {
    return [
        'user_id' => '461d2fa0-b2a4-11e9-bb9d-098867e21a32',
        'order_id'           =>  (string) \Illuminate\Support\Str::orderedUuid(),
        'game_id'            =>  'GDArOyYB5dneMdPp',
        'game_login'         =>  'endgame',
        'amount'             => 10,
        'cash_amount'        => 100,
        'transaction_status' => 9,
    ];
});
