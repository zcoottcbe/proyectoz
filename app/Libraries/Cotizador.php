<?php


namespace App\Libraries;

interface Cotizador
{
  public function precio(\App\Orden $orden);
}
