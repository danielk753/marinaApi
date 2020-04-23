<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{

    public function listar(Request $request)
    {
        $productos = Producto::all();
        return response()->json($productos, 200);
    }
    public function  almacenar(Request $request)
    {
        $this->validate($request, [
            'codigo_producto' => 'required|max:255|unique:productos,codigo_producto'
        ]);
        $producto = new Producto();
        $producto->fill($request->all());
        if ($request->imagen) {
            $producto->imagen=$this->setImage($request);
        }
        $producto->save();
        return response()->json($producto, 201);
    }
    public function  mostrar(Request $request, $id)
    {
        if ($request->isJson()) {
            $producto = Producto::find($id);
            return response()->json($producto, 201);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function  actualizar(Request $request, $id)
    {
        $this->validate($request, [
            'codigo_producto' => "required|max:255|unique:productos,codigo_producto,$id"
        ]);
        $producto = Producto::find($id);
        $producto->fill($request->all());
        if($request->imagen){
            $producto->imagen=$this->setImage($request,$producto->imagen);
        }
        $producto->save();
        return response()->json($producto, 200);
    }
    public function  eliminar(Request $request, $id)
    {
        $producto = Producto::find($id);
        $imageName=$producto->imagen;
        $imageName=explode('images/',$imageName)[1]; 
        Storage::delete("images/".$imageName);
        $producto->delete();
        return response()->json($producto, 200);
    }
    
    public function setImage($request,$path=null){
        if($path){
            $path=explode('images/',$path);
            Storage::delete("images/".$path[1]);
        }
        $imagen = $request->imagen;
        $extension = explode(';base64,',$imagen);
        $imagen = $extension[1];
        $extension = explode('/',$extension[0]);
        $extension=".".$extension[1];
        $imagen = str_replace(' ', '+', $imagen);
        $imageName = uniqid().$extension;
        Storage::put('images/' . $imageName, base64_decode($imagen));
        return 'http://167.71.87.228/storage/images/' . $imageName;
    }
}
