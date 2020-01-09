<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Game\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'ID' => mt_rand(10000, 999999),
        'truename' => $faker->name,
        'name' => $faker->userName,
        'passwd' => hash_passwd(strtolower($faker->userName), 'secret'),
        'email' => $faker->freeEmail,
        'creatime' => now(),
    ];
});
