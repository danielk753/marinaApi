<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticketsventa extends Model
{
    //
    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }
}
