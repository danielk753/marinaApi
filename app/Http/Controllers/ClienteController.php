<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function listar()
    {
        $clientes = Cliente::where('visible', true)->get();
        return response()->json($clientes, 200);
    }

    public function almacenar(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo|max:255",
            "telefono" => "required|integer|unique:agentes,telefono|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $cliente = new Cliente();
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 201);
    }

    public function mostrar(Request $request, Cliente $cliente)
    {
        return response()->json($cliente, 201);
    }

    public function actualizar(Request $request, Cliente $cliente)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo,$cliente->id|max:255",
            "telefono" => "required|integer|unique:agentes,telefono,$cliente->id|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 200);
    }

    public function eliminar(Request $request, Cliente $cliente)
    {
        $cliente->visible = false;
        $cliente->save();
        return response()->json($cliente, 200);
    }
}
