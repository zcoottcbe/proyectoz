<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\Servicio;

/* Pendientes de prepagos
FIXME implementar soporte de fecha de expiracion (MainWindow.css:353)
FIXME implementar fecha de expiracion (MainWindow:372)
FIXME implementar busqueda de id_operacion (MainWindow:380)
FIXME para categorias con precio personalizable, no se checa el precio
 actual de la categoria
FIXME soporte de cambio de categoria
 */
class Boleto extends Model
{
    protected $table = "casetaXXX";
    protected $primaryKey = "boleto_id";

    function taquilla() {
      $taquilla_nombre = substr($this->getTable(), 6);
      $taquilla = Taquilla::where("nombre", $taquilla_nombre)
        ->first();
      $this->taquilla_id = $taquilla->id;
      return $this->belongsTo('App\Taquilla', 'taquilla_id');
    }

    function categoria() {
      return $this->belongsTo("App\Categoria", 'catbol_id');
    }

    function cliente() {
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }


    function getCodigoLargoAttribute() {
      $table = substr($this->getTable(), 6);
      return "+$table+$this->seguridad+$this->id";
    }

    function getCodigoAttribute() {
      $id = substr($this->id, -3);
      return sprintf("+%02d%s%s", 
        $this->taquilla->id, $this->seguridad, $id);
    }

    // Prepagos
    static function TipoDeCodigo($codigo) {
      $firstchar = mb_substr($codigo, 0, 1);
      if(!in_array($firstchar, ['+', "¿", '¡']))
        return false;

      $codigo = mb_ereg_replace("(¿|¡)", "+", $codigo); 
      $split = mb_split("\+", $codigo);

      // los codigos validos tienen 1 o 3 signos de +
      // el +1 de abajo es porque estamos contando los bloques separados por +
      if(count($split)==2) return "Corto";
      else if(count($split)==4) return "Largo";
    }

    static function buscarCodigoCorto($codigo) {
      $codigo = mb_ereg_replace("(¿|¡)", "+", $codigo); 
      list($nothing, $codigo) = mb_split("\+", $codigo);
      $length = mb_strlen($codigo);

      $taquilla = Taquilla::find(mb_substr($codigo, 0, 2));
      if(!$taquilla) return;

      $seguridad = mb_substr($codigo,  2, $length-5);
      $boleto_id_parcial = mb_substr($codigo, $length-3);

      return $taquilla->boletoQuery()
        ->where("seguridad", $seguridad)
        //->where("preticket", 1)
        ->whereRaw("substr(boleto_id, -least(3, length(boleto_id)))=cast(? as unsigned)", $boleto_id_parcial)
        ->first();
    }

    static function buscarCodigoLargo($codigo) {
      $codigo = mb_ereg_replace("(¿|¡)", "+", $codigo); 
      list(
        $nothing, 
        $taquilla_nombre, 
        $seguridad, 
        $boleto_id
      ) = mb_split("\+", $codigo);

      $taquilla = Taquilla::where('nombre', $taquilla_nombre)->first();

      return $taquilla->boletoQuery()
        ->where("seguridad", $seguridad)
        ->where("boleto_id", $boleto_id)
        //->where("preticket", 1)
        ->first();
    }

    static function buscarPrepago($codigo) {
      $tipo = self::TipoDeCodigo($codigo);
      if(!$tipo) return null;

      return self::{"buscarCodigo$tipo"}($codigo);
    }
    
    function getPrepagosRelacionadosAttribute() {
      if(!isset($this->id_operacion)) 
        return collect([$this]);

      return $this->taquilla->boletoQuery()
        ->where("id_operacion", $this->id_operacion)
        ->where("status", 0)
        ->get();
    }

    function prepagoUsado() {
      if(isset($this->_prepagoUsado)) return $this->_prepagoUsado;

      $codigo = $this->codigoLargo;

      return $this->_prepagoUsado = (Session::prepagoUsado($codigo) 
        ?: Taquilla::prepagoUsado($codigo));
    }

}
