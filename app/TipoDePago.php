<?php

namespace App;

class TipoDePago
{
  static $data = [
    "CONTADO"  =>["codigo"=>"CONTADO", "nombre"=>"Contado", "pagado"=>true, "id"=>0],
    "T.CREDITO"=>["codigo"=>"T.CREDITO", "nombre"=>"Tarjeta de crédito", "pagado"=>false, "id"=>1],
    "AGENCIA"  =>["codigo"=>"AGENCIA", "nombre"=>"Credito", "pagado"=>true, "id"=>2],
    "PREPAGO"  =>["codigo"=>"PREPAGO", "nombre"=>"Prepago (abonos)", "pagado"=>true, "id"=>3],
    "CREDITO GLOBAL"=>["codigo"=>"CREDITO GLOBAL", "nombre"=>"Agencias Contado", "pagado"=>true, "id"=>4],
    "DEPOSITO"      =>["codigo"=>"DEPOSITO", "nombre"=>"Agencias Credito", "pagado"=>true, "id"=>5],
    "VENTAWEB"      =>["codigo"=>"VENTAWEB", "nombre"=>"Venta Web", "pagado"=>true, "id"=>4],
  ];

  static function find($codigo) {
    if(!isset(self::$data[$codigo]))
      throw new \Exception("No existe el tipo de pago $data");
    $model = new TipoDePago;
    foreach(self::$data[$codigo] as $k=>$v) 
      $model->$k = $v;

    return $model;
  }

    static function all() {
        return collect(self::$data)
            ->map(function($data, $codigo) {
                return self::find($codigo);
            });
    }

    static function session() {
        return self::all()->map(function($tipo, $key) {
            $taquilla = \App\Session::taquilla();
            $tienePrepagos = \App\Session::tienePrepagos();
            $tipo->active = false;

            if($tienePrepagos) {
                $total = \App\Session::resumen()["total"];
                if($key=="CONTADO") {
                    $tipo->sensitive = $taquilla->aceptaContado && $total>0;
                    $tipo->active = $total >0;
                } elseif($key=="DEPOSITO") {
                    $tipo->sensitive = false;
                } elseif($key=="T.CREDITO") {
                    $tipo->sensitive = $taquilla->aceptaCredito && $total>0;
                } elseif($key=="AGENCIA") {
                    $tipo->sensitive = $taquilla->aceptaAgencia && $total>0;
                } elseif($key=="CREDITO GLOBAL") {
                    $tipo->sensitive = false;
                } elseif($key=="PREPAGO") {
                    $tipo->sensitive = true;
                    $tipo->active = $total==0;
                }
                if($key=="VENTAWEB") {
                    $tipo->sensitive = false;
                }
            // } else if($tieneVentaWeb) { // TODO
            } else {
                if($key=="CONTADO") {
                    $tipo->sensitive = $taquilla->aceptaContado;
                    $tipo->active = true;
                } elseif($key=="DEPOSITO") {
                    $tipo->sensitive = $taquilla->aceptaDeposito;
                } elseif($key=="T.CREDITO") {
                    $tipo->sensitive = $taquilla->aceptaCredito;
                } elseif($key=="AGENCIA") {
                    $tipo->sensitive = $taquilla->aceptaAgencia;
                } elseif($key=="CREDITO GLOBAL") {
                    $tipo->sensitive = $taquilla->aceptaCreditoGlobal;
                } elseif($key=="PREPAGO") {
                    $tipo->sensitive = $taquilla->aceptaPrepago;
                } elseif($key=="VENTAWEB") {
                    $tipo->sensitive = false;
                }
            }
            $input_attr = [];
            $label_attr = [];
            if(!$tipo->sensitive) {
                $input_attr[] = 'disabled';
                $label_attr[] = 'class="text-muted"';
            }
            if($tipo->active) {
                $input_attr[] = 'checked';
            }
            $tipo->inputHtml = join(" ", $input_attr);
            $tipo->labelHtml = join(" ", $label_attr);
            return $tipo;
        });
    }

}
