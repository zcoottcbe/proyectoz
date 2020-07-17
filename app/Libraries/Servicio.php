<?php

namespace App\Libraries;
use App\Libraries\Model;
use App\Taquilla;
use App\Moneda;

interface Servicio {
  public function setPrecio(Taquilla $taquilla, Moneda $moneda, $precio, $tup);

  public function nombre();
  public function columnaNombre();
  public function columnaInfo();
  public function tieneCodigo($codigo);

  public function esPersonalizable();
}
