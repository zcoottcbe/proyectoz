<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Cliente;
use App\Taquilla;
use App\Moneda;
use App\Libraries\Servicio;

class Session {
  static function taquilla(?Taquilla $taquilla=null) {
    if(!$taquilla) return Taquilla::find(session('taquilla_id'));
    session()->put("taquilla_id", $taquilla->id);
  }

  static function moneda(?Moneda $moneda=null) {
    if(!$moneda) return Moneda::find(session()->get('moneda_id', 1));
    session()->put("moneda_id", $moneda->id);
  }

  static function categorias() {
    $taquilla_id = session('taquilla_id');

    Taquilla::find($taquilla_id)->categorias;
  }

  // necesitamos categoria, cantidad
  static function add(Orden $orden) {
    // FIXME: verificar si el cliente actual puede usar esta categoria
    // o si la taquilla actual la puede usar
    $ordenes = session("ordenes", []);
    if(isset($orden->saveIndex)) 
        $ordenes[$orden->saveIndex] = $orden;
    else
        $ordenes[] = $orden;

    session()->put("ordenes", $ordenes);

    return redirect("/");
  }


  static function resumen() {
    return array_reduce(self::list(), function($carry, $x) {
      $carry['cantidad'] += $x->cantidad;
      $carry['precio'] += $x->precio['precio'];
      $carry['total'] += $x->precio['precio']*$x->cantidad;
      return $carry;
    }, ['cantidad'=>0, 'precio'=>0, 'total'=>0]);
  }

  static function list() {
    $taquilla = Taquilla::find(session('taquilla_id'));
    $moneda = Moneda::find(session('moneda_id'));

    return session("ordenes", []);
  }

  static function remove($index) {
    $ordenes = session("ordenes");
    unset($ordenes[$index]);
    session()->put("ordenes", array_values($ordenes));
  }

  static function clear() {
    session()->forget("ordenes");
  }

  static function save(
      User $user,
      MetodoDePago $metodoDePago, 
      FormaDePago $formaDePago, 
      UsoCFDI $usoCFDI,
      TipoDePago $tipoDePago,
      $prepago,
      $roundtrip
    ) {

   

    $taquilla = Taquilla::find(session('taquilla_id'));
    $moneda = Moneda::find(session('moneda_id'));
    $time = time();
    $fecha = Carbon::now();
    $guardados = [];

    DB::beginTransaction();

    //$valores = Session::list();
    

    //echo '<br><h1>Valores: '. var_dump(Session::list()).' termina valores</h1>';


    foreach(Session::list() as $orden) {
      
      $servicio = $orden->servicio;
      $precio_mxn = $servicio->precio($orden);
      


      echo '<h3>Arreglo: <br>';
      //echo $servicio->columnaNombre().'<br>';
      //var_dump($orden->servicio);
      //var_dump($servicio->categoria);
      //echo "precio dll: ".$orden->servicio->precio_cobrado_dolares;
      echo "<br>2 precio".$servicio->categoria->precio_cobrado_dolares;
      echo 'Fin de arreglo</h3>';



      for($f=0; $f<$orden->cantidad*($roundtrip? 2: 1); $f++) {
        $nombre = $servicio->categoria->nombre; 
        
        
        if(!$nombre) $nombre = $servicio->categoria->nombre;
        
        $precio = $orden->precio;


        $row = [
          "grupo_id"=>$time,
          "preciofact"=>$precio_mxn["precio"],
          "ivafact"=>$precio_mxn["ivafact"],
          "precio"=>$moneda->id==1? $precio["precio"]: 0,
          "tup"=>$moneda->id==1? $precio["tup"]: 0,
          "iva"=>$moneda->id==1? $precio["iva"]: 0,
          "preciousd"=>$moneda->id==2? $servicio->categoria->precio_cobrado_dolares: 0,

          "tupusd"=>$moneda->id==2? $precio["tup"]: 0,
          "ivausd"=>$moneda->id==2? $precio["iva"]: 0,
          "moneda_id"=>$moneda->id,
          "nombre"=>$servicio->nombre(),
          "fecha"=>Carbon::now(),
          "status"=>0,
          "facturado"=>0,
          "catbol_id"=>$servicio->categoria->catbol_id,
          "auth_id"=>0, //FIXME
          "ruta_id"=>0, //FIXME
          "usr_id"=>$user->id,
          "hora"=>"0.00", //FIXME
          "puerto_id"=>$taquilla->muelle->puerto_id,
          "cliente_id"=>$servicio->cliente? $servicio->cliente->cliente_id: 0,
          "info"=>"", //FIXME
          "ruta_nom"=>"",
          "catbol_nom"=>$servicio->columnaNombre(),
          "id_venta"=>"0", //FIXME
          "id_venta_descripcion"=>"0", //FIXME
          "tipo_pago"=>$tipoDePago->id,
          "pagado"=>$tipoDePago->pagado,
          "factura"=>"",
          "fact_codigocliente"=>"",
          "fact_serie"=>"",
          "fact_folio"=>"0",
          "fact_moneda"=>$moneda->id==1? "MXN": "USD",
          "fact_conductor"=>"", //FIXME
          "fact_declaracioncarga"=>"",
          "fact_rutahora"=>"", //FIXME
          "fact_tipopago"=>"", //FIXME
          "fact_rfc"=>"",
          "fact_numext"=>"",
          "fact_estado"=>"",
          "fact_pais"=>"",
          "fact_codigopostal"=>"",
          "fact_regimen"=>$metodoDePago->nombre,
          "fact_lugarexpedicion"=>$taquilla->nombre,
          "fact_cuentabancaria"=>"",
          "fact_nombre"=>"",
          "fact_calle"=>"",
          "fact_ciudad"=>"",
          "fact_colonia"=>"",
          "fact_colonia"=>"",
          "pago_contado"=>"0", //FIXME
          "pago_credito"=>"0", //FIXME
          "pago_agencia"=>"0", //FIXME
          "pago_prepago"=>"0", //FIXME
          "pago_creditoglobal"=>"0", //FIXME
          "pago_deposito"=>"0", //FIXME
          "prepagado"=>0, //FIXME
          "seguridad"=>$taquilla->seguridad(), //FIXME
          "preticket_folio"=>"", //FIXME
          "formapago"=>$formaDePago->codigo, //FIXME
          "usocfdi"=>$usoCFDI->codigo, //FIXME
          "total_sin_desc"=>$precio["precio_base"], //FIXME?
          "pcnt_desc"=>0, //FIXME
          "pcnt_iva"=>$taquilla->muelle->puerto->pcnt_iva,
          "base_categoria"=>$precio["precio"],
          "precio_original"=>$precio["precio"],
          "preticket"=>$prepago or ($roundtrip && $f%2==1),
        ];
          
        if ($servicio->columnaNombre()==''){
          $row["preticket_folio"]=$servicio->categoria->codigoLargo;
        }
        else{
           if(substr($servicio->columnaNombre(),0,1)=="+"){
            $row["preticket_folio"]= $orden->servicio->datos["preticket_folio"];
           }
           else{
            $row["preticket_folio"]= "";
           }
        }


        if(env("NEOPOS_VEHICULOS")){
            foreach($orden->servicio->datos as $k=>$v) {
              $row[$k] = $v;
            }
        }

        $table = "caseta{$taquilla->nombre}";

        if(Schema::hasColumn($table, 'fecha_modificacion')) 
          $row["fecha_modificacion"] = Carbon::now();
        if(Schema::hasColumn($table, "id_operacion"))
          $row["id_operacion"] = $time;
        if(Schema::hasColumn($table, "preticket_folio_origen")) {
          $prepago_split = explode("+", $servicio->categoria->codigoLargo);
          $row["preticket_folio_origen"] = count($prepago_split)>1
            ? $prepago_split[1]
            : "";
        }
        

        $id = DB::table($table)
          ->insertGetId($row);

        DB::table($table)
          ->where('boleto_id', $id)
          ->update(['folio'=>sprintf("%02d%07d", $taquilla->id, $id)]); 
        $guardados[] = $id;
      }
    }
    DB::commit();
    return $guardados;
  }

  static function prepagoUsado($codigo) {
    return collect(session("ordenes", []))
      ->reduce(function($usado, $orden)  use($codigo) {
        if($usado) return $usado;
        if($orden->servicio->tieneCodigo($codigo))
            return ["lugar"=>"Sesión", "fecha"=>""];

      }, null);
  }

    static function tienePrepagos() {
        return collect(self::list())->reduce(function($tiene, $x) {
            if($tiene) return true;
            return !empty($x->servicio->datos["preticket_folio"]);
        }, false);
    }

  static function generaPrepagos() {
    $taquilla = self::taquilla();
    return !self::tienePrepagos() and $taquilla->generaPrepagos; 
  }
  static function generaRoundtrips() {
      $taquilla = self::taquilla();
      return $taquilla->generaRoundtrips;
  }

    static function crearOrden(Servicio $servicio, $cantidad=1) {
        $orden = new Orden($servicio, 
            self::taquilla(),
            self::moneda(),   
            $cantidad
        );
        self::setOrdenActiva($orden);
    }


  static function setOrdenActiva($orden) {
      session()->put("orden_activa", $orden);
  }
  static function getOrdenActiva() {
    return session("orden_activa", null);
  }
}
