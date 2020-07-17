<?php

namespace App\Http\Middleware;

use Closure;

class Taquilla
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
        $taquilla_id = $request->session()->get('taquilla_id');

        if(
            !$taquilla_id ||
            !\App\Taquilla::find($taquilla_id)
        ) {
          return redirect()->route('taquilla.asignar');
        }
        return $next($request);
    }
}
