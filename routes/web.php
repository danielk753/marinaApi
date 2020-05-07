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

Route::get('/api/productos', ['uses' => 'ProductoController@listar']);
Route::post('/api/productos', ['uses' => 'ProductoController@almacenar']);
Route::get('/api/productos/{id}', ['uses' => 'ProductoController@mostrar']);
Route::put('/api/productos/{id}', ['uses' => 'ProductoController@actualizar']);
Route::delete('/api/productos/{id}', ['uses' => 'ProductoController@eliminar']);

Route::get('/api/clientes', ['uses' => 'ClienteController@listar']);
Route::post('/api/clientes', ['uses' => 'ClienteController@almacenar']);
Route::get('/api/clientes/{id}', ['uses' => 'ClienteController@mostrar']);
Route::put('/api/clientes/{id}', ['uses' => 'ClienteController@actualizar']);
Route::delete('/api/clientes/{id}', ['uses' => 'ClienteController@eliminar']);

Route::get('/api/agentes', ['uses' => 'AgenteController@listar']);
Route::post('/api/agentes', ['uses' => 'AgenteController@almacenar']);
Route::get('/api/agentes/{id}', ['uses' => 'AgenteController@mostrar']);
Route::put('/api/agentes/{id}', ['uses' => 'AgenteController@actualizar']);
Route::delete('/api/agentes/{id}', ['uses' => 'AgenteController@eliminar']);

Route::get('/api/tickets', 'CompraController@listarTickets');
Route::get('tickets/nombresAgentes', 'CompraController@listarNombreAgentes');
