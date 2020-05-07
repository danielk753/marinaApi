<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Compra::class, function (Faker $faker) {
    return [
        'subtotal' => $faker->randomFloat(2, 1, 50),
        'iva' => $faker->randomFloat(2, 1, 10),
        'total' => $faker->randomFloat(50, 80),
        'aplicado' => $faker->boolean,
        'cantidad_unitaria' => $faker->numberBetween(0, 20),
        'producto_id' => $faker->numberBetween(1, 50),
        'ticket_id' => $faker->numberBetween(1, 50)
    ];
});
