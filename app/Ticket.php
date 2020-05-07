<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    public function agente()
    {
        return $this->belongsTo('App\Agente');
    }

    public function compras()
    {
        return $this->hasMany('App\Compra');
    }

}
