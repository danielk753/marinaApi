<?php

namespace App\Http\Controllers;

use App\Compra;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    //
    public function listarTickets()
    {
        $tickets=Ticket::all();
        foreach ($tickets as $i => $ticket){
            $id=$ticket->agente_id;
            $ticket->agente_id=['nombre'=>$ticket->agente->nombre,'id'=>$id];
        }
        return response()->json($tickets,200);
    }
    public function listarNombreAgentes(){
        $names= DB::select('select id,nombre from Agentes');
        return response()->json($names,200);
    }
}
