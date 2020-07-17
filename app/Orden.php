<?php

namespace App;

use App\Libraries\Servicio;
use App\Libraries\Cotizador;

class Orden {
    function __construct(Servicio $servicio, 
        Taquilla $taquilla, 
        Moneda $moneda,
        $cantidad) {
        $this->servicio = $servicio;
        $this->cantidad = $cantidad;
        $this->taquilla = $taquilla;
        $this->moneda = $moneda;
        $this->cotizador = collect([]);
        $this->agregarCotizador($servicio);
        $this->recalcular();
  }

    function agregarCotizador(Cotizador $cotizador) {
        $className = get_class($cotizador);
        if($this->cotizador->has($className)) 
            return;

        $this->cotizador->put($className, $cotizador);
    }

  function recalcular() {
      $this->precio = $this->cotizador->reduce(function($carry, $cot) {
          return $this->precio = $cot->precio($this);
      }, []);
  }

  function total() {
    return $this->precio["precio"]*$this->cantidad;
  }
}
