<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Producto::class, function (Faker $faker) {
    $stock_minimo= $faker->numberBetween(1,50);

    return [
        'nombre' => $faker->word,
        'codigo_producto' => $faker->ean13,
        'descripcion' => $faker->text(100),
        'marca'=> $faker->word,
        'fecha_caducidad'=>$faker->date('Y-m-d'),
        'ubicacion'=>$faker->secondaryAddress,
        'unidad_medida'=>$faker->word,
        'presentacion'=>$faker->word,
        'stock_minimo'=>$stock_minimo,
        'stock_maximo'=>$faker->numberBetween($stock_minimo,50),
        'stock_actual'=>$faker->numberBetween(1,50),
        'costo'=>$faker->randomFloat(2,1,1000),
        'iva'=>$faker->randomFloat(2,1,1000),
        'precio'=>$faker->randomFloat(2,1,1000),
        'utilidad_bruta'=>$faker->randomFloat(2,1,1000),
        'descuento'=>$faker->randomFloat(2,1,1000),
        'comision'=>$faker->randomFloat(2,1,1000),
        'imagen'=>$faker->imageUrl(640,480),

    ];
});
