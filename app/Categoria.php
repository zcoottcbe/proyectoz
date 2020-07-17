<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;
use App\Libraries\Servicio;

class Categoria extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_catboleto';
  protected $primaryKey = 'catbol_id';


    public function precio(Taquilla $taquilla, Moneda $moneda) {
        $puerto_id = $taquilla->muelle->puerto_id;
        $moneda_id = $moneda->id;
        
        $data = [
            "precio_base" => (float) $this->{"precio{$puerto_id}_1_$moneda_id"},
            "tup" => (float) $this->{"tup{$puerto_id}_$moneda_id"},
            "iva" => (float) $this->{"iva{$puerto_id}_$moneda_id"},
            "dll" => (float) $this->{"precio{$puerto_id}_1_2"},
            "ivadll" => (float) $this->{"iva{$puerto_id}_2"},
            "tupdll" => (float) $this->{"tup{$puerto_id}_2"},
            "mxn" => (float) $this->{"precio{$puerto_id}_1_1"},
            "ivamxn" => (float) $this->{"iva{$puerto_id}_1"},
            "tupmxn" => (float) $this->{"tup{$puerto_id}_1"},
            "moneda" => $moneda_id,
        ];

        if ($moneda_id==1){ //Pesos
            $data["totalactual"] = $data["mxn"]+$data["ivamxn"]+$data["tupmxn"];
        }
        else{
            $data["totalactual"] = $data["dll"]+$data["ivadll"]+$data["tupdll"];
        }
        $data["precio"] = $data["precio_base"]+$data["tup"]+$data["iva"];
        $data["ivafact"] = $data["iva"]+($data["tup"]-$data["tup"]/(1+$taquilla->muelle->puerto->pcnt_iva/100));


        return collect($data)->transform(function($val) {
            return round($val, 2);
        });
    }

    public function setPrecio(
        Taquilla $taquilla, 
        Moneda $moneda, 
        $precio, 
        $tup
    ) {
        $puerto_id = $taquilla->muelle->puerto_id;
        $pcnt_iva = $taquilla->muelle->puerto->pcnt_iva;
        $moneda_id = $moneda->id;
        $precio_sin_tup = $precio-$tup;
        $iva = $precio_sin_tup-$precio_sin_tup/(1+$pcnt_iva/100);
        
        $this->{"tup{$puerto_id}_$moneda_id"} = $tup;
        $this->{"iva{$puerto_id}_$moneda_id"} = $iva;
        $this->{"precio{$puerto_id}_1_$moneda_id"} = $precio-$tup-$iva;
    }

}
