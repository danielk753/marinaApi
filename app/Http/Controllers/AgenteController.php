<?php

namespace App\Http\Controllers;

use App\Agente;
use Illuminate\Http\Request;

class AgenteController extends Controller
{
    public function listar(Request $request)
    {
        $agentes = Agente::all();
        return response()->json($agentes, 201);
    }

    public function almacenar(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo|max:255",
            "telefono" => "required|integer|unique:agentes,telefono|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $agente = new Agente();
        $agente->fill($request->json()->all());
        $agente->save();
        return response()->json($agente, 201);
    }

    public function mostrar(Request $request, $id)
    {
        $cliente = Agente::find($id);
        return response()->json($cliente, 201);
    }

    public function actualizar(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo,$id|max:255",
            "telefono" => "required|integer|unique:agentes,telefono,$id|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $agente = Agente::find($id);
        $agente->fill($request->all());
        $agente->save();
        return response()->json($agente, 200);
    }

    public function eliminar(Request $request, $id)
    {
        $agente = Agente::find($id);
        $agente->delete();
        return response()->json($agente, 200);
    }
}
