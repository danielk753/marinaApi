<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\Ticketsventa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    //
    public function getProductos()
    {
        $productos = Producto::where('visible', true)->get();
        return response()->json($productos, 200);
    }

    public function getClientes()
    {
        $clientes = Cliente::where('visible', true)->get();
        return response()->json($clientes, 200);
    }

    public function setVenta(Request $request)
    {
        $idTicket = -1;
        DB::transaction(function () use ($request, &$idTicket) {

            $idTicket = DB::table('ticketsventas')->insertGetId([
                'subtotal' => $request->subtotal,
                'iva' => $request->iva,
                'total' => $request->total,
                'cliente_id' => isset($request['cliente']) ? $request->cliente : null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
            foreach ($request->ventas as $venta) {
                DB::table('ventas')->insert([
                    'subtotal' => $venta['subtotal'],
                    'iva' => $venta['iva'],
                    'total' => $venta['total'],
                    'cantidad_unitaria' => $venta['cantidad'],
                    'producto_id' => $venta['producto']['id'],
                    'ticketsventa_id' => $idTicket,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]);
            }
        });
        $ticketventa = Ticketsventa::find($idTicket);
        return response()->json($ticketventa, 200);
    }
}
