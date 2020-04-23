<?php

namespace App\Http\Controllers;
use App\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function listar(Request $request)
    {
        $clientes = Cliente::all();
        return response()->json($clientes, 200);
    }
    public function  almacenar(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo|max:255",
            "telefono" => "required|integer|unique:agentes,telefono|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $cliente = new Cliente();
        $cliente->fill($request->json()->all());
        $cliente->save();
        return response()->json($cliente, 201);
    }
    public function  mostrar(Request $request, $id)
    {
        $cleinte = Cliente::find($id);
        return response()->json($cleinte, 201);
    }
    public function  actualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo,$id|max:255",
            "telefono" => "required|integer|unique:agentes,telefono,$id|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $cliente = Cliente::find($id);
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 200);
    }
    public function  eliminar(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        $cliente->delete();
        return response()->json($cliente, 200);
    }
}
