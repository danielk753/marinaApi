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
        $tickets = Ticket::all();
        foreach ($tickets as $ticket) {
            $id = $ticket->agente_id;
            $ticket->agente_id = ['nombre' => $ticket->agente->nombre, 'id' => $id];
        }
        return response()->json($tickets, 200);
    }

    public function listarNombreAgentes()
    {
        $names = DB::select('SELECT id, nombre FROM agentes ORDER BY nombre ASC');
        return response()->json($names, 200);

    }

    public function listarproductosForAdd()
    {
        $productos = DB::select('SELECT CONCAT(codigo_producto,\' - \',nombre,\' - \',presentacion,\' - \',unidad_medida) as descripcion,
                    id, iva, costo, (costo-iva) as subototal FROM productos');
        return response()->json($productos, 200);
    }

    public function setCompra(Request $request)
    {
        $this->validate($request, [
            'fecha_solicitud' => ['required'],
            'fecha_entrega' => ['required'],
            'factura' => ['required'],
            'subtotal' => ['required'],
            'iva' => ['required'],
            'total' => ['required'],
            'agente_id' => ['required'],
            'aplicar' => ['required'],
            'productos.*.id' => ['required'],
            'productos.*.subtotal' => ['required'],
            'productos.*.iva' => ['required'],
            'productos.*.costo' => ['required'],
            'productos.*.cantidad' => ['required']
        ]);
        $idTicket = -1;
        DB::transaction(function () use ($request, &$idTicket) {
            $idTicket = DB::table('tickets')->insertGetId([
                'fecha_solicitud' => $request->fecha_solicitud,
                'fecha_entrega' => $request->fecha_entrega,
                'numero_factura' => $request->factura,
                'subtotal' => $request->subtotal,
                'iva' => $request->iva,
                'total' => $request->total,
                'agente_id' => $request->agente_id,
                'aplicado' => $request->aplicar,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]);
            foreach ($request->productos as $producto) {
                DB::table('compras')->insert([
                    'subtotal' => $producto['subtotal'],
                    'iva' => $producto['iva'],
                    'total' => $producto['costo'],
                    'aplicado' => $request['aplicar'],
                    'cantidad_unitaria' => $producto['cantidad'],
                    'producto_id' => $producto['id'],
                    'ticket_id' => $idTicket,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]);
            }
        });
        $newTicket = Ticket::find($idTicket);
        $id = $newTicket->agente_id;
        $newTicket->agente_id = ['nombre' => $newTicket->agente->nombre, 'id' => $id];
        return response()->json($newTicket, 201);
    }

    public function getCompra(Ticket $ticket)
    {
        $ticketToSend = $ticket->getAttributes();
        $ticketToSend['productos'] = [];
        foreach ($ticket->compras as $compra) {
            $producto['descripcion'] = $compra->producto->codigo_producto . ' - ' . $compra->producto->nombre . ' - ' . $compra->producto->presentacion . ' - ' . $compra->producto->unidad_medida;
            $producto['id'] = $compra->producto->id;
            $producto['iva'] = $compra->producto->iva;
            $producto['costo'] = $compra->total;
            $producto['subtotal'] = $compra->subtotal;
            $producto['cantidad'] = $compra->cantidad_unitaria;
            $producto['id_compra'] = $compra->id;
            $ticketToSend['productos'][] = $producto;
        }
        $ticketToSend['factura'] = $ticket->numero_factura;
        $ticketToSend['aplicar'] = $ticket->aplicado;
        return response($ticketToSend, 200);
    }

    public function updateCompra(Request $request)
    {
        $this->validate($request, [
            'fecha_solicitud' => ['required'],
            'fecha_entrega' => ['required'],
            'factura' => ['required'],
            'subtotal' => ['required'],
            'iva' => ['required'],
            'total' => ['required'],
            'agente_id' => ['required'],
            'aplicar' => ['required'],
            'productos.*.id' => ['required'],
            'productos.*.subtotal' => ['required'],
            'productos.*.iva' => ['required'],
            'productos.*.costo' => ['required'],
            'productos.*.cantidad' => ['required']
        ]);
        $ticket = Ticket::find($request->id);
        $arrayIdsCompras = [];
        $arrayOldIdsCompras = DB::table('compras')->select('id')->where('ticket_id', '=', $ticket->id)->get();
        $toInsert = [];
        foreach ($request->productos as $comp) {
            if (isset($comp['id_compra'])) {
                $arrayIdsCompras[] = $comp['id_compra'];
            } else {
                $toInsert[] = $comp['id'];
            }
        }
        foreach ($arrayOldIdsCompras as $key => $comp) {
            $arrayOldIdsCompras[$key] = $comp->id;
        }
        $arrayOldIdsCompras = $arrayOldIdsCompras->all();
        $toDelete = array_diff($arrayOldIdsCompras, $arrayIdsCompras);

        DB::transaction(function () use ($request, $toDelete, $toInsert) {
            DB::table('tickets')->where('id', $request->id)->update([
                'fecha_solicitud' => $request->fecha_solicitud,
                'fecha_entrega' => $request->fecha_entrega,
                'numero_factura' => $request->factura,
                'subtotal' => $request->subtotal,
                'iva' => $request->iva,
                'total' => $request->total,
                'agente_id' => $request->agente_id,
                'aplicado' => $request->aplicar,
            ]);
            foreach ($toDelete as $idDelete) {
                DB::table('compras')->where('id', '=', $idDelete)->delete();
            }
            foreach ($request->productos as $producto) {
                if (!isset($producto['id_compra'])) {
                    DB::table('compras')->insert([
                        'subtotal' => $producto['subtotal'],
                        'iva' => $producto['iva'],
                        'total' => $producto['costo'],
                        'aplicado' => $request['aplicar'],
                        'cantidad_unitaria' => $producto['cantidad'],
                        'producto_id' => $producto['id'],
                        'ticket_id' => $request->id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                } else {
                    DB::table('compras')->where('producto_id', '=', $producto['id'])->update([
                        'subtotal' => $producto['subtotal'],
                        'iva' => $producto['iva'],
                        'total' => $producto['costo'],
                        'aplicado' => $request['aplicar'],
                        'cantidad_unitaria' => $producto['cantidad'],
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                }

            }
        });
        $ticket = $ticket->fresh();
        $id = $ticket->agente_id;
        $ticket->agente_id = ['nombre' => $ticket->agente->nombre, 'id' => $id];
        return response()->json($ticket, 201);
    }

    public function deleTicket(Ticket $ticket)
    {
        DB::transaction(function () use ($ticket) {
            DB::table('compras')->where('ticket_id', '=', $ticket->id)->delete();
            DB::table('tickets')->where('id', '=', $ticket->id)->delete();
        });
        return response()->json($ticket, 200);
    }

}
