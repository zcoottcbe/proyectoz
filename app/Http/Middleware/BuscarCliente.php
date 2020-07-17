<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Response;

use Closure;
use App\Cliente;

class BuscarCliente
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
        $texto = $request->input('buscar');
        if(strlen($texto)<3)  {
            return $next($request);
        }

        $clientes = Cliente::where('buscable', 1)->
            where(function($query) use ($texto) {
                $query->where('nombre', 'like', "%$texto%")
                    ->orWhere('rfc', 'like', "%$texto%");
            })
            ->orderBy('nombre')
            ->get();

        if($clientes->count() == 0)
            return $next($request);

        return response()->view('cliente.buscar', compact('clientes'));
    }
}
