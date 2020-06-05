<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Ticketsventa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    //
    public function getVentas()
    {
        $ticketsventas = Ticketsventa::all();
        foreach ($ticketsventas as $ticket) {
            if (!is_null($ticket->cliente_id)) {
                $id = $ticket->cliente_id;
                $ticket->cliente_id = ['nombre' => $ticket->cliente->nombre, 'id' => $id];
            }
            $ticket['fecha'] = explode(' ', $ticket->created_at)[0];
            $ticket['fecha'] .= " " . explode(' ', $ticket->created_at)[1];
        }
        return response()->json($ticketsventas, 200);
    }

    public function deleteVenta(Request $request)
    {
        $ticketventa = Ticketsventa::find($request->id);
        DB::transaction(function () use ($ticketventa) {
            DB::table('ventas')->where('ticketsventa_id', '=', $ticketventa->id)->delete();
            DB::table('ticketsventas')->where('id', '=', $ticketventa->id)->delete();
        });
        return response()->json($ticketventa, 200);
    }

    public function getClientes()
    {
        $clientes = Cliente::where('visible', true)->get();
        return response()->json($clientes, 200);
    }

    public function getTicket(Ticketsventa $ticketsventa)
    {
        $ticketToSend = $ticketsventa->getAttributes();
        $ticketToSend['productos'] = [];
        foreach ($ticketsventa->ventas as $venta) {
            $producto['descripcion'] = $venta->producto->codigo_producto . ' - ' . $venta->producto->nombre;
            $producto['id'] = $venta->producto->id;
            $producto['iva'] = $venta->producto->iva;
            $producto['costo'] = $venta->total;
            $producto['subtotal'] = $venta->subtotal;
            $producto['cantidad'] = $venta->cantidad_unitaria;
            $producto['id_compra'] = $venta->id;
            $producto['visible'] = $venta->producto->visible;
            $ticketToSend['productos'][] = $producto;
        }
        $ticketToSend['cliente'] = $ticketsventa->cliente;
        return response($ticketToSend, 200);
    }

    public function updateCliente(Request $request)
    {
        $ticketsventa = Ticketsventa::find($request->id);
        $ticketsventa->cliente_id = $request->cliente_id;
        $ticketsventa->save();
        $id = $ticketsventa->cliente_id;
        $ticketsventa->cliente_id = ['nombre' => $ticketsventa->cliente->nombre, 'id' => $id];
        $ticketsventa['fecha'] = explode(' ', $ticketsventa->created_at)[0];
        $ticketsventa['fecha'] .= " " . explode(' ', $ticketsventa->created_at)[1];

        return response()->json($ticketsventa, 200);
    }
}
