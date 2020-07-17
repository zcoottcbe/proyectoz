<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;

use App\Categoria;

class Taquilla extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_taquilla';
  protected $primaryKey = 'taquilla_id';


  public function muelle() {
    return $this->belongsTo('App\Muelle', 'muelle_id');
  }

  public function categorias() {
    $categorias = explode(",", $this->catbol_id);
    return Categoria::whereIn("catbol_id", $categorias)
      ->where('boton', 1)
      ->orderBy('orden')
      ->orderBy('nombre')
      ->get();
  }

  public function botones() {
    // FIXME: me gustaria que esto sea una relacion en vez de una consulta
    // (asi como la funcion categorias())
    // siento que sería mejor, pero no podria decir por que ahorita.
      return Categoria::whereIn('catbol_id', explode(',', $this->catbol_id))
        ->where('boton', 1)
        ->orderBy('orden')
        ->orderBy('nombre')
        ->get();
  }

  public function getAceptaCreditoAttribute() {
    return $this->pago_credito=='t';
  }
  public function getAceptaContadoAttribute() {
    return $this->pago_contado=='t';
  }
  public function getAceptaAgenciaAttribute() {
    return $this->pago_agencia=='t';
  }
  public function getAceptaPrepagoAttribute() {
    return $this->pago_prepago=='t';
  }
  public function getAceptaDepositoAttribute() {
    return $this->pago_deposito=='t';
  }
  public function getAceptaCreditoGlobalAttribute() {
    return $this->pago_creditoglobal=='t';
  }
  public function getGeneraPrepagosAttribute() {
    return $this->pago_preticket=='t';
  }
  public function getGeneraRoundtripsAttribute() {
      return $this->roundtrip=='t';
  }

  public function getEditaInformacionAttribute() {
    return $this->edita_info=='t';
  }

  public function getPorcentajeIvaAttribute() {
      return $this->muelle->puerto->pcnt_iva;
  }

  public function puertos() {
    return DB::table('cmc_puerto AS p')
      ->join('cmc_muelle AS m', 'm.puerto_id', '=', 'p.puerto_id')
      ->where('m._hidden', 0)
      ->where('p._hidden', 0)
      ->whereRaw('find_in_set(muelle_id, ?)', $this->muelle_id)
      ->get();
  }

  public function canalesDeVenta() {
    return DB::table('cmc_canal_venta')
      ->whereRaw('find_in_set(id, ?)', $this->canal_venta_id)
      ->where("_hidden", 0)
      ->get();
  }

  public function seguridadDisponible($seguridad) {
    return DB::table("caseta$this->nombre")
      ->where('seguridad', $seguridad)
      ->count()==0;
  }

  public function seguridad() {
    while(true) {
      $seguridad = sprintf("%06d%02d", rand(0, 999999), Carbon::now()->year-2000);
      if($this->seguridadDisponible($seguridad))
        return $seguridad;
    }
  }

  public function boletoQuery() {
    return (new Boleto)->setTable("caseta$this->nombre");
  }

  static function prepagoUsado($codigo) {
    return self::all()->reduce(function($usado, $taquilla) 
      use ($codigo) {
      if($usado) 
        return $usado;

      $boleto = $taquilla->boletoQuery()
        ->where('preticket_folio', $codigo)
        ->first();
      if($boleto)
          return ["lugar"=>$taquilla->nombre, "fecha"=>$boleto->fecha];
    }, null);
  }
}
