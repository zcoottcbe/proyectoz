<?php

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
  Session::reflash();
  return redirect()->route('categorias');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// rutas que necesitan solo que el usuario este loggeado
Route::middleware(['auth'])->group(function() {
    Route::get('/taquilla/asignar', 'TaquillaController@asignar')
      ->name('taquilla.asignar');

    Route::get ('/sesion/taquilla/{taquilla}', 'SesionController@taquilla')
        ->name('sesion.taquilla');
});

// rutas que necesitan que el usuario este loggeado y que tenga taquilla
// asignada a la sesión
Route::middleware(['auth', 'taquilla'])->group(function() {
    Route::get ('/categorias/', 'CategoriaController@indice')
      ->name('categorias');

    Route::post('/taquilla/buscar', 'TaquillaController@buscar')
        ->middleware(
            'buscar.prepago', 
            'buscar.credencial', 
            'buscar.cliente'
        )
      ->name('taquilla.buscar');

    Route::get('/sesion/agregar/{categoria}/{cantidad?}', 'CategoriaController@agregar')
      ->name('categoria.agregar');

    Route::get ('/sesion/limpiar', 'SesionController@limpiar')
      ->name('sesion.limpiar');
    Route::get ('/sesion/revisar', 'SesionController@revisar')
      ->name('sesion.revisar');
    Route::post('/sesion/guardar', 'SesionController@guardar')
      ->name('sesion.guardar');

    Route::get('/orden/editar', 'OrdenController@editar')
        ->middleware(
            'orden.personalizar', 
            'orden.vehiculo'
        )
      ->name('orden.editar');
    Route::post('/orden/personalizar', 'OrdenController@personalizar')
        ->name('orden.personalizar');
    Route::get('/orden/reeditar/{id}', 'OrdenController@reeditar')
        ->name('orden.reeditar');

    Route::get('/sesion/moneda/{moneda}', 'SesionController@moneda')
      ->name('sesion.moneda');

    Route::get('/cliente/credencial', 'ClienteController@credencial')
      ->name('cliente.credencial');
    Route::post('/cliente/credencialAgregar', 'ClienteController@credencialAgregar')
      ->name('cliente.credencialAgregar');

    Route::get('/prepago/agregar/{prepago}/{categoria?}', 
      'PrepagoController@agregar')
      ->name('prepago.agregar');

    Route::post('/vehiculo/guardar', 'VehiculoController@guardar')
        ->name('vehiculo.guardar');

    Route::get ('/sesion/redimirboletos', 'SesionController@redimirboletos')
      ->name('sesion.redimirboletos');

    Route::get ('/sesion/imprimirboletos/', 'SesionController@imprimirboletos')
      ->name('sesion.imprimirboletos');

    Route::get('/print/verRegistros/', 'PrintController@verRegistros')
      ->name('print.verregistros');  

});
