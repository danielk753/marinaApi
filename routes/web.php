<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Productos
Route::get('/api/productos', ['uses' => 'ProductoController@listar']);
Route::post('/api/productos', ['uses' => 'ProductoController@almacenar']);
// Route::get('/api/productos/{id}', ['uses' => 'ProductoController@mostrar']);
Route::put('/api/productos/{producto}', ['uses' => 'ProductoController@actualizar']);
Route::delete('/api/productos/{producto}', ['uses' => 'ProductoController@eliminar']);

// Clientes
Route::get('/api/clientes', 'ClienteController@listar');
Route::post('/api/clientes', 'ClienteController@almacenar');
// Route::get('/api/clientes/{id}', 'ClienteController@mostrar']);
Route::put('/api/clientes/{cliente}', 'ClienteController@actualizar');
Route::delete('/api/clientes/{cliente}', 'ClienteController@eliminar');

// Proveedores
Route::get('/api/agentes', ['uses' => 'AgenteController@listar']);
Route::post('/api/agentes', ['uses' => 'AgenteController@almacenar']);
// Route::get('/api/agentes/{agente}', ['uses' => 'AgenteController@mostrar']);
Route::put('/api/agentes/{agente}', 'AgenteController@actualizar');
Route::delete('/api/agentes/{agente}', ['uses' => 'AgenteController@eliminar']);

Route::get('/api/tickets', 'CompraController@listarTickets');
Route::get('/api/tickets/nombresAgentes', 'CompraController@listarNombreAgentes');
Route::get('/api/tickets/productosForAdd', 'CompraController@listarproductosForAdd');
Route::post('/api/tickets/setProductos', 'CompraController@setCompra');
Route::get('/api/tickets/getCompra/{ticket}', 'CompraController@getCompra');
Route::post('/api/tickets/updateCompra', 'CompraController@updateCompra');
Route::delete('/api/tickets/{ticket}', 'CompraController@deleTicket');


