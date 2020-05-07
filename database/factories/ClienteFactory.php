<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Cliente::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'correo' => $faker->unique()->safeEmail,
        'telefono' => $faker->e164PhoneNumber,
        'direccion' => $faker->address
    ];
});
