<?php

namespace App\Http\Middleware;

use Closure;
use App\Session;

class OrdenVehiculo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // si no se deben capturar vehiculos, saltarse esta etapa
        if(!env("NEOPOS_VEHICULOS")) return $next($request);

        $orden = Session::getOrdenActiva();

        // si ya se le cargaron datos de vehiculo a la orden, entonces
        // continuar a la siguiente etapa
        if(isset($orden->vehiculoCapturado)) return $next($request);

        // de otra manera, mostrar la pagina de captura de datos de vehiculo
        $taquilla = \App\Session::taquilla();
        $categorias = $orden->servicio->categorias();
        $datos = $orden->servicio->datos;

        return response()->view('vehiculo/forma', 
            compact('orden','categorias', 'datos'));
    }
}
