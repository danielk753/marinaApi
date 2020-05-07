<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Ticket::class, function (Faker $faker) {
    return [
        'fecha_solicitud'=>$faker->date('Y-m-d'),
        'fecha_entrega'=>$faker->date('Y-m-d'),
        'numero_factura'=>$faker->ean13,
        'subtotal'=>$faker->randomFloat(2,1,50),
        'iva'=>$faker->randomFloat(2,1,10),
        'total'=>$faker->randomFloat(2,50,80),
        'aplicado'=>$faker->boolean,
        'agente_id'=>$faker->numberBetween(1,50)
    ];
});
