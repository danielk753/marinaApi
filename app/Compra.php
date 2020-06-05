<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    //
    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }
}
