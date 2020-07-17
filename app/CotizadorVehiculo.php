<?php

namespace App;

class CotizadorVehiculo implements \App\Libraries\Cotizador {
    private function precio_largo(Orden $orden) {
        $taquilla = $orden->taquilla;
        $categoria = Categoria::where('nombre', 'Excedente_Longitud')
            ->first();
        return $categoria->precio($taquilla, Moneda::find(1));
    }

    private function precio_pax(Orden $orden) {
        $taquilla = $orden->taquilla;
        $categoria = Categoria::where('nombre', 'Pax_Extra')
            ->first();
        return $categoria->precio($taquilla, Moneda::find(1));
    }

    private function precio_ancho(Orden $orden) {
        return collect($orden->precio)
            ->map(function($x) { return $x/2; });
    }    
    
    public function precio(Orden $orden) {
        // multiplicar todos los valores por las cantidades apropiadas
        $datos_ancho = collect($this->precio_ancho($orden))
            ->transform(function($v, $k)  use($orden) {
            return $v*($orden->servicio->datos["ancho_extra"]? 1: 0);
        });
        $orden->servicio->datos["ancho_extra"] = $datos_ancho["precio"];

        $datos_pax = collect($this->precio_pax($orden))
            ->transform(function($v, $k) use($orden) {
            return $v*$orden->servicio->datos["pax_extra"];
        });

        $datos_largo = collect($this->precio_largo($orden))
            ->transform(function($v, $k) use($orden) {
            return $v*$orden->servicio->datos["excedente_longitud"];
        });

        // ahora sumar todos los valores
        return collect($orden->precio)->map(function($x, $k) 
            use ($datos_pax, $datos_largo, $datos_ancho) {
            return $x + $datos_pax[$k] + $datos_largo[$k] + $datos_ancho[$k];
        });
    }
}
