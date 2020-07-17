<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boleto;
use App\Categoria;
use App\Session;
use App\Orden;

class PrepagoController extends Controller
{
    function agregar(Boleto $prepago, $categoria=null)
    {
        if(!$prepago->prepagoUsado()) {
            $servicio = new \App\ServicioPrepago($prepago, $categoria);
            $servicio->datos["placas"] = $prepago->placas;
            $servicio->datos["operador"] = $prepago->operador;
            $servicio->datos["pase_abordar"] = $prepago->pase_abordar;
            $servicio->datos["preticket_folio"] = $prepago->codigoLargo;

            Session::crearOrden($servicio, 1);
            return redirect()->route('orden.editar');
        }

        return redirect("/");
    }
}
