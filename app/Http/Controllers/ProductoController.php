<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{

    public function listar()
    {
        $productos = Producto::where('visible', true)->get();
        return response()->json($productos, 200);
    }

    public function almacenar(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'codigo_producto' => 'required|max:255|unique:productos,codigo_producto',
            'fecha_caducidad' => 'required'
        ]);
        $producto = new Producto();
        $producto->fill($request->all());
        if ($request->imagen) {
            $producto->imagen = $this->setImage($request);
        }
        $producto->save();
        return response()->json($producto, 201);
    }

    public function mostrar(Request $request, Producto $producto)
    {
        return response()->json($producto, 201);

    }

    public function actualizar(Request $request, Producto $producto)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'codigo_producto' => "required|max:255|unique:productos,codigo_producto,$producto->id"
        ]);
        $producto->fill($request->all());
        if ($request->imagen && $producto->imagen != $request->imagen) {
            $producto->imagen = $this->setImage($request, $producto->imagen);
        }
        $producto->save();
        return response()->json($producto, 200);
    }

    public function eliminar(Request $request, Producto $producto)
    {
        if ($producto->imagen) {
            $imageName = $producto->imagen;
            $imageName = explode('images/', $imageName);
            //todo: corregir acceso a null
            if (count($imageName) > 1) {
                Storage::delete("images/" . $imageName[1]);
            }
        }
//        $producto->delete();
        $producto->visible = false;
        $producto->save();
        return response()->json($producto, 200);
    }

    public function setImage($request, $path = null)
    {
        if ($path) {
            $path = explode('images/', $path);
            if (count($path) > 1) {
                Storage::delete("images/" . $path[1]);
            }
        }
        $imagen = $request->imagen;
        $extension = explode(';base64,', $imagen);
        $imagen = $extension[1];
        $extension = explode('/', $extension[0]);
        $extension = "." . $extension[1];
        $imagen = str_replace(' ', '+', $imagen);
        $imageName = uniqid() . $extension;
        Storage::put('images/' . $imageName, base64_decode($imagen));
        return 'http://167.71.87.228/storage/images/' . $imageName;
    }
}
