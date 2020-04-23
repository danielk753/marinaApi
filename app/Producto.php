<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo_producto','descripcion','marca','fecha_caducidad','ubicacion','unidad_medida','presentacion','stock_minimo','stock_maximo','stock_actual','costo','iva','precio','utilidad_bruta','descuento','comision',
    ];

    protected $hidden = [
        'password',
    ];
}
