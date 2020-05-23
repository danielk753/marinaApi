<?php

namespace App\Http\Controllers;

use App\Agente;
use Illuminate\Http\Request;

class AgenteController extends Controller
{
    public function listar()
    {
        $agentes = Agente::all();
        return response()->json($agentes, 201);
    }

    /*
    * Almacenar una proveedor
    *
    * @param Request $request
    * @return Response
    */
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

    public function actualizar(Request $request, Agente $agente)
    {
        $this->validate($request, [
            'nombre' => 'required|max:255',
            "correo" => "unique:agentes,correo,$agente->id|max:255",
            "telefono" => "required|integer|unique:agentes,telefono,$agente->id|digits_between:1,20",
            "direccion" => "max:255"
        ]);
        $agente->fill($request->all());
        $agente->save();
        return response()->json($agente, 200);
    }

    public function eliminar(Request $request, Agente $agente)
    {
        $agente->delete();
        return response()->json($agente, 200);
    }

//    public function mostrar(Request $request, $agente)
//    {
//        return response()->json($agente, 201);
//    }
}
