<?php 

namespace App;

class ServicioPublico implements \App\Libraries\Servicio,
    \App\Libraries\Cotizador
{
    function __construct(Categoria $categoria) {
        $this->categoria = $categoria;
        $this->cliente = null;
        $this->datos = [
            "descuento" => 0
        ];
    }

    public function precio(Orden $orden) {
        $factorDescuento = 1-($this->datos["descuento"]/100);
        $precio = $this->categoria->precio($orden->taquilla, $orden->moneda);
        
        return collect($precio)->transform(function($precio) 
            use($factorDescuento) {
            return $precio*$factorDescuento;
        });
    }

    public function setPrecio(
        Taquilla $taquilla, 
        Moneda $moneda, 
        $precio, 
        $tup
    ) {
        return $this->categoria->setPrecio($taquilla, $moneda, $precio, $tup);
    }

    public function esPersonalizable() {
        return $this->categoria->custom;
    }


    public function nombre() {
        return $this->categoria->nombre;
    }

    public function columnaNombre() {
        return "";
    }

    public function columnaInfo() {
        return @$this->datos["info"];
    }

    public function tieneCodigo($codigo) {
        return false;
    }

    public function categorias() {
        return \App\Session::taquilla()->categorias();
    }
}
