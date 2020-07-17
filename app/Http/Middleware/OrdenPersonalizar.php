<?php

namespace App\Http\Middleware;

use Closure;
use App\Session;
use App\Moneda;

class OrdenPersonalizar
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
      $orden = Session::getOrdenActiva();
      $taquilla = Session::taquilla();
      $precio_mxn = $orden->servicio->precio($orden);
      $precio_usd = $orden->servicio->precio($orden);

      if($orden->servicio->esPersonalizable() 
          and !isset($orden->personalizada)) 
        return response()->view('orden.personalizar', 
          compact('orden', 'precio_mxn', 'precio_usd'));

      return $next($request);
    }
}
