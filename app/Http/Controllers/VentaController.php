<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\Ticket;
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

    public function getEventos()
    {
        $eventos = [];
        $productos = Producto::count();
        if ($productos) {
            $productos = Producto::where('visible', true)->get();
            foreach ($productos as $producto) {
                $evento['title'] = "Caduca el producto " . $producto->codigo_producto . " " . $producto->nombre;
                $evento['color'] = 'red';
                $evento['date'] = $producto->fecha_caducidad;
                $eventos[] = $evento;
            }
        }
        $tickets = Ticket::count();
        if ($tickets) {
            $tickets = Ticket::all();
            foreach ($tickets as $ticket) {
                $evento['title'] = "Entrega de productos comprados con id " . $ticket->id . " del proveedor " . $ticket->agente->nombre;
                $evento['color'] = 'aquamarine';
                $evento['date'] = $ticket->fecha_entrega;
                $eventos[] = $evento;
            }
        }
        return response()->json($eventos, 200);
    }

    public function getMes()
    {
        $meses = DB::select("SELECT SUM(total)AS total,MONTH(created_at) AS 'mes' FROM ticketsventas WHERE YEAR(created_at) ='2020' GROUP BY MONTH(created_at)");
        return response()->json($meses, 200);
    }

    public function getTablas()
    {
        $clientes = DB::select("SELECT c.nombre, COUNT(*) AS 'total' FROM ticketsventas t JOIN clientes c ON t.cliente_id = c.id GROUP BY c.nombre ORDER BY total DESC  LIMIT 5 ");
        $productos = DB::select("SELECT SUM(total)AS total, p.nombre  FROM ventas v JOIN productos p ON p.id =v.producto_id GROUP BY producto_id ORDER BY total DESC LIMIT 5");
        $tablas['clientes'] = $clientes;
        $tablas['productos'] = $productos;
        return response()->json($tablas, 200);
    }

}
