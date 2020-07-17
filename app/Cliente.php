<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\Servicio;
use App\Libraries\SoftDeletes;
use App\Categoria;

use Carbon\Carbon;

class Cliente extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_cliente';
  protected $primaryKey = 'cliente_id';


  function scopeCredencial($query, $credencial) {
    return $query
      ->where('credencial', $credencial);
  } 

  function getInactivoAttribute($query) {
    if($this->status!=0) 
      return "Cliente suspendido.";

    if($this->baja!=null and $this->baja<Carbon::now()) 
      return 'Periodo de expedición especial vencido';

    if($this->boletos_limite >=0 
      and $this->boletos_contador>$this->boletos_limite)
      return "La credencial ya superó su limite de $this->boletos_limite usos.";
  }

  public function categorias() {
    if($this->inactivo)
      return collect([]);

    if($this->catbol_id)
        return Categoria::where("catbol_id", $this->catbol_id)
          ->orWhere("parent", $this->catbol_id)
          ->orderBy('orden')
          ->orderBy('nombre')
          ->get();
    else
        return \App\Session::taquilla()->categorias();
  }

  public function crearServicio(Categoria $categoria) {
            $servicio = new \App\ServicioCliente($this, 
                $categoria->catbol_id);
            $servicio->datos["catbol_id"] = $categoria->catbol_id;
            if(!env("NEOPOS_VEHICULOS")){
              $servicio->datos["placas"] = '';
              $servicio->datos["excedente_longitud"] = 0;
              $servicio->datos["extra"] = 0;
              $servicio->datos["largo"] = 0;
              $servicio->datos["info"] = '';
              $servicio->datos["operador"] = '';
              $servicio->datos["pase_abordar"] = '';
              $servicio->datos["pax_extra"] = 0;
              $servicio->datos["ancho_extra"] = 0;
              $servicio->datos["descuento"] = 0;
            } /*zlcb*/
            else
            {
              $servicio->datos["placas"] = $this->placas;
              $servicio->datos["excedente_longitud"] = $this->vehiculo_exlongitud;
              $servicio->datos["extra"] = $this->vehiculo_extra;
              $servicio->datos["largo"] = $this->vehiculo_largo;
              $servicio->datos["info"] = $this->vehiculo_info;
              $servicio->datos["operador"] = $this->vehiculo_operador? $this->vehiculo_operador: $this->nombre;
              $servicio->datos["pase_abordar"] = $this->vehiculo_pase_abordar;
              $servicio->datos["pax_extra"] = $this->vehiculo_pax;
              $servicio->datos["ancho_extra"] = $this->vehiculo_ancho_extra;
              $servicio->datos["descuento"] = $this->descto;
            }
            return $servicio;
  }

}
