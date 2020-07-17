<?php 

namespace App;

class ServicioPrepago extends ServicioPublico {
    function __construct(Boleto $boleto, Categoria $categoria=null) {
        parent::__construct($categoria?: $boleto->categoria);

        $this->boleto = $boleto;
        $this->servicio_original = new ServicioPublico($boleto->categoria);
        $this->servicio_original->datos["descuento"] = $boleto->descuento;
        $this->cliente = $boleto->cliente;
        $this->datos["descuento"] = $boleto->descuento;
    }

    function tieneCodigo($codigo) {
      return $this->boleto->codigoLargo === $codigo;
    }

    function esPersonalizable() {
        return false;
    }

    function precio(Orden $orden) {
        $taquilla = $orden->taquilla;
        $moneda = $orden->moneda;
        // obtener la diferencia entre el precio de la categoria que se pago
        // inicialmente y el precio de la categoria que se esta seleccionando
        // en la interfaz
        // FIXME: esto no aplica en categoria->custom==1
        $actual = parent::precio($orden);
        $prepago = $this->servicio_original->precio($orden);

        foreach($prepago as $k => $v) $actual[$k] -= $v;
        return $actual;
    }

    function setPrecio(Taquilla $taquilla, Moneda $moneda, $precio, $tup) {
        throw(new Exception("No se puede cambiar el precio de un prepago"));
    }

    function columnaNombre() {
      return $this->boleto->codigo;
    }
}
