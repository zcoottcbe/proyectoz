<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Session;
use App\Orden;
use App\Moneda;

class OrdenController extends Controller
{
    // Este metodo agrega la orden a la sesión, tiene varios
    // middlewares que son procesados primero, y no van a dejar
    // que el usuario llegué a esta parte a menos de que se 
    // cumplan las condiciones apropiadas, como la captura de 
    // datos adicionales o datos de vehiculo cuando corresponda
    function editar() {
      Session::add(Session::getOrdenActiva());
      return redirect('/');
    }

    function reeditar($index) {
        $lista = Session::list();
        $orden = $lista[$index];
        unset($orden->personalizada);
        unset($orden->vehiculoCapturado);
        $orden->saveIndex = $index;
        Session::setOrdenActiva($orden);
        return redirect()->route("orden.editar");
    }

    function personalizar(Request $request) {
        $taquilla = Session::taquilla();

        $orden = Session::getOrdenActiva();
        $orden->servicio->setPrecio($taquilla, Moneda::find(1), 
            $request->input('precio'), $request->input('tup'));
        $orden->servicio->setPrecio($taquilla, Moneda::find(2), 
            $request->input('preciousd'), $request->input('tupusd'));
        $orden->servicio->datos["info"] = $request->input('info');
        $orden->personalizada = true;
        //Session::setOrdenActiva($orden);
        return redirect()->route('orden.editar');
    }

    function olvidar() {
      Session::setOrdenActiva(null);
      return redirect('/');
    }

}
