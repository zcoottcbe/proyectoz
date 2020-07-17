<?php

namespace App\Http\Middleware;

use Closure;
use App\Boleto;

class BuscarPrepago
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
        //+2206926920147
        $prepago = Boleto::buscarPrepago($request->input('buscar'));

        if(!$prepago) return $next($request);
        $usado = $prepago->prepagoUsado();
       
        if(!$usado)
            return redirect()->route("prepago.agregar", [$prepago->codigo]);
        if($prepago->getPrepagosRelacionadosAttribute()->count() > 1) {
            //return request()->view('prepago.relacionados', compact('prepago'));
            //echo '<h1>aqui debe llamar la vista</h1>';
        }
        
        

        return response()->view("prepago.index", compact('prepago', 'usado'));
    }
}
