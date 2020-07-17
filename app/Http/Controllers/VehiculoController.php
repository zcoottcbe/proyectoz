<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Categoria;
use App\CotizadorVehiculo;

class VehiculoController extends Controller
{
    function guardar(Request $request) {
        $orden = \App\Session::getOrdenActiva();
        $taquilla = \App\Session::taquilla();

        $servicio = $orden->servicio;
        $servicio->datos["placas"] = $request->input("placas");
        $servicio->datos["operador"] = $request->input("operador");
        $servicio->datos["pase_abordar"] = $request->input("pase_abordar");
        $servicio->datos["pax_extra"] = (int) $request->input("pax_extra");
        $servicio->datos["largo"] = (float) $request->input("largo");
        $servicio->datos["excedente_longitud"] = (float) $request->input("excedente_longitud");
        $servicio->datos["extra"] = $request->input("extra");
        $servicio->datos["ancho_extra"] = $request->input("ancho_extra");
        $servicio->datos["descuento"] = (float) $request->input("descuento");
        $servicio->datos["info"] = $request->input("info");
        $servicio->datos["catbol_id"] = $request->input("catbol_id");

        $validator = Validator::make($request->all(), [
            'catbol_id'=>'required',
            'operador'=>'required',
            'placas' => [
                'required',
                // las placas solo pueden tener letras o numeros
                'regex:/^[A-Z-a-z0-9]+$/',
            ],
            'pase_abordar' => [
                'required',
                (
                    $taquilla->aceptaPrepago?
                    // si la taquilla acepta prepagos, el pase de abordar
                    // solo tiene que ser mas largo de cuatro caracteres
                    'regex:/^.{4,}$/':

                    // de otra manera, puede ser 5 o mas digitos
                    // o algunas algo asi como abc-123-123
                    'regex:/^([0-9]{5,}|[A-Za-z]+-[0-9]+-[0-9]+)$/'
                )
            ]
        ]);
        //FIXME: no deberia sobrescribir la categoria desde aqui
        // deberia tener un metodo para cambiar la categoria
        $orden->servicio->categoria = Categoria::find($servicio->datos["catbol_id"]);
        $orden->agregarCotizador(new CotizadorVehiculo);
        $orden->recalcular();

        if($validator->fails()) {
            return redirect()
                ->route('orden.editar')
                ->withErrors($validator);
        }
        $orden->vehiculoCapturado = true;
        return redirect()->route("orden.editar");
    }
}
