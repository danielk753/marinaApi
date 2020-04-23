<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agente extends Model
{
    protected $fillable = [
        'nombre','direccion','correo','telefono'
    ];
    protected $hidden = [
        'password',
    ];
}
