<?php
namespace App;

class Precio {
    function __construct($precio, $tup, $iva, $pcnt_iva) {
        $this->precio = $precio;
        $this->tup = $tup;
        $this->iva = $iva;
        $this->precio_base = $precio-$tup-$iva;
        $this->ivafact = $this->iva+($this->tup-$this->tup/(1+$pcnt_iva/100));
    }
}
