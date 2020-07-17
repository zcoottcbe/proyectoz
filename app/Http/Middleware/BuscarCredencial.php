<?php

namespace App\Http\Middleware;

use Closure;
use App\Cliente;
use App\Session;
use App\Orden;

class BuscarCredencial
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
        $cliente = Cliente::credencial($request->input('buscar'))
            ->first();

        if(!$cliente) {
            return $next($request);
        }

        $categorias = $cliente->categorias();

        if($categorias->count() == 1) {
            $categoria = $categorias->first();
            Session::crearOrden($cliente->crearServicio($categoria));
            return redirect()->route('orden.editar');
        }

        return response()->view('cliente.mostrar', compact('cliente', 'categorias'));
    }
}
