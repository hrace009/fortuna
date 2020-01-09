<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Ticket::class, function (Faker $faker) {
    return [
        'subject'     => $this->faker->sentence,
        'ip_address'  => '127.0.0.1',
        'category_id' => 1,
        'message'     => $this->faker->text,
    ];
});
