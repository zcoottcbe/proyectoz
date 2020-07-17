<?php
namespace apiv1;

class VentaWebController extends \BaseController {
  function cliente($id)
  {
    $conn = \DB::connection("server");

    $cliente = $conn->table("cmc_cliente")
      ->where("cliente_id", $id)
      ->whereRaw("case when baja is null then 1 else baja>now() end")
      ->first();

    if(!$cliente)
    {
      return array(
        "errors"=>array(
          "title"=>"No se encontro el cliente",
          "detail"=>"El cliente $id no ha sido encontrado",
          "status" => 2
        )
      );
    }

    $cliente = $conn->table("cmc_cliente")
      ->where("cliente_id", $id)
      ->whereRaw("case when baja is null then 1 else baja>now() end")
      ->where("status", 0)
      ->first();

    if(!$cliente)
    {
      return array(
        "errors"=>array(
          "title"=>"Cliente inactivo",
          "detail"=>"El cliente $id esta inactivo",
          "status" => 4
        )
      );
    }

    $categoria = $conn->table("cmc_catboleto")
      ->where("catbol_id", $cliente->catbol_id)
      ->first();

    if($categoria->precio3_1_1==0) {
      return array(
        "errors"=>array(
          "title"=>"No se puede usar dicha credencial",
          "detail"=>"La credencial es una cortesia",
          "status" => 3
      ));
    }

    $fecha_baja = date_parse($cliente->baja);
    if($fecha_baja["error_count"] == 0 && checkdate($fecha_baja["month"], $fecha_baja["day"], $fecha_baja["year"])) {
      if(strtotime($cliente->baja) > strtotime(date('Y-m-d H:i:s'))) {
        $tarifa_id = $cliente->catbol_id;
      }
      else {
        $tarifa_id = $cliente->catbol2_id;
      }
    }
    else {
      $tarifa_id = $cliente->catbol_id;
    }

    return array(
      "id"=>$id,
      "nombre"=>$cliente->nombre,
      "tipo_vehiculo"=>$categoria->nombre,
      "placa"=>isset($cliente->placas)?$cliente->placas:"",
      "precio"=>$categoria->precio3_1_1,
      "status" => 1,
      "tarifa_id"=>$tarifa_id
    );
  }

  function cliente_ic($id)
  {
    $conn = \DB::connection("server");

    $cliente = $conn->table("cmc_cliente")
      ->where("cliente_id", $id)
      ->whereRaw('case when baja is null then 1 else baja>now() end')
      ->first();

    if(!$cliente)
    {
      return array(
        "errors"=>array(
          "title"=>"No se encontro el cliente",
          "detail"=>"El cliente $id no ha sido encontrado",
          "status" => 2
        )
      );
    }

    $cliente = $conn->table("cmc_cliente")
      ->where("cliente_id", $id)
      ->where('status', 0)
      ->whereRaw('case when baja is null then 1 else baja>now() end')
      ->first();

    if(!$cliente)
    {
      return array(
        "errors"=>array(
          "title"=>"El cliente esta inactivo",
          "detail"=>"El cliente $id esta inactivo",
          "status" => 5
        )
      );
    }

    $categoria = $conn->table("cmc_catboleto")
      ->where("catbol_id", $cliente->catbol_id)
      ->first();

    if($categoria->precio3_1_1==0) {
      return array(
        "errors"=>array(
          "title"=>"No se puede usar dicha credencial",
          "detail"=>"La credencial es una cortesia",
          "status" => 3
      ));
    }
    return array(
      "id"=>$id,
      "nombre"=>$cliente->nombre,
      "tipo_vehiculo"=>$categoria->nombre,
      "placa"=>isset($cliente->placas)?$cliente->placas:"",
      "precio"=>$categoria->precio3_1_1,
      "baja"=>$cliente->baja,
      "status" => 1,
      "catbol_id"=>$cliente->catbol_id
    );
  }


  function categorias()
  {
    $conn = \DB::connection("server");
    $categorias = $conn->table("cmc_catboleto")
    ->select("catbol_id as id", "nombre","precio3_1_1 as precio1","iva3_1 as iva",\DB::raw("precio3_1_1+iva3_1 as precio"),\DB::raw("CONCAT('/imagenes/categoria_', catbol_id, '.jpg') as imagen"))
        ->where("_hidden", 0)
        ->where("websale", 1)
        ->orderBy("nombre")
        ->get();
    return $categorias;
  }

  function generar()
  {
    return \DB::transaction(function() {
      $params = ["cliente_id",
      "tipo_operacion",
      "origen",
      "id_operacion",
      "categorias",
      "datos",
      "precio_total",
      "puerto_id"];

      foreach($params as $param) {
        if(!\Input::has($param)) {
          return array(
            "errors"=>array(
              "title"=>"Parametros incompletos",
              "detail"=>"El parametro: '" . $param . "' es requerido",
              "status" => 7
            )
          );
        }
      }

      $params2 = ["fecha", "horario", "operador", "placa", "puerto", "pax"];

      foreach($params2 as $param) {
        if(!array_key_exists($param, \Input::get("datos"))) {
          return array(
            "errors"=>array(
              "title"=>"Parametros incompletos",
              "detail"=>"El parametro:  datos[" . $param . "] es requerido",
              "status" => 7
            )
          );
        }
      }

      $cliente_id = \Input::get("cliente_id");
      $tipo_operacion = \Input::get('tipo_operacion');
      $origen = \Input::get('origen');
      $id_operacion = \Input::get('id_operacion');
      $categorias  = \Input::get("categorias");
      $datos = \Input::get("datos");
      $precio_total = \Input::get("precio_total");
      $grupo = \BoletoWeb::generaGrupoId();
      //$puerto_id = intval(\Input::get('puerto_id', 3));
      $puerto_id = intval(\Input::get('puerto_id'));
      $precio = "precio${puerto_id}_1_1";


      $iva = "iva${puerto_id}_1";
      $tup = "tup${puerto_id}_1";

      if(trim($id_operacion) != "") {
        $conn = \DB::connection("local");
        $boletos_id_operacion = $conn->table("casetaVENTAWEB")
          ->select(\DB::raw("COUNT(boleto_id) as total"))
          ->where("id_operacion", $id_operacion)
          ->first();

        if($boletos_id_operacion->total > 0) {
          return array(
            "result" => "error",
            "detail" => "Ya se generaron boletos con el id_operacion"
          );
        }
      }

      preg_match("@(\d+)/(\d+)/(\d+)@", $datos["fecha"], $match);
      $fecha_reservacion = "$match[3]-$match[2]-$match[1] $datos[horario]";

      foreach($categorias as $id=>$cantidad)
      {
        if(!$this->validaDisponibilidad($id,
          $puerto_id,
          $fecha_reservacion,
          $cantidad)) {
            return array(
              "errors"=>array(
                "title"=>"No hay disponibilidad",
                "detail"=>"No existe disponibilidad para la categoria: " . $id,
                "status" => 6
              )
            );
        }
      }

      $boletos = array();

      $ticketfolio = null;
      \Log::info("VentaWeb", $_POST);

      $server = \DB::connection("server");
      $pax_extra_cat = $server->table('cmc_catboleto')
           ->where('nombre', 'Pax_extra')
           ->first();
      $exced_long_cat = $server->table('cmc_catboleto')
         ->where('nombre', 'Excedente_Longitud')
         ->first();
       $cliente = $server->table("cmc_cliente")
         ->where("cliente_id", $cliente_id)
         ->first();
       $puerto = $server->table("cmc_puerto")
         ->where("puerto_id", $puerto_id)
         ->first();

      if(isset($datos["pax"]))
      {
          $categorias[$pax_extra_cat->catbol_id] = $datos["pax"];
      }

      $result = array(
        "categorias"=>array(),
        "folios"=>array(),
      );

      $result["total"] = 0;
      $result["iva"] = 0;

      foreach($categorias as $id=>$cantidad)
      {
        $categoria = \DB::connection("server")
          ->table("cmc_catboleto")
          ->where("catbol_id", $id)
          ->first();

        $multiplicador = 1;
        if($categoria->nombre == "Excedente_Longitud") {
          $cantidad = 1;
          $multiplicador = $cantidad;
        }

        for($f=0; $f<$cantidad; $f++) {
          $boleto = new \BoletoWeb;
          if(isset($datos["nombres"]) && isset($datos["nombres"][$categoria->catbol_id]) &&count($datos["nombres"][$categoria->catbol_id])>0) {
              $nombre = array_shift($datos["nombres"][$categoria->catbol_id]);
          }
          else {
            $nombre = "Sin nombre";
          }
          $boleto->grupo_id = $grupo;
          if($tipo_operacion=="reservaIC") {
               preg_match("@(\d+)/(\d+)/(\d+)@", $datos["fecha"], $match);
               $boleto->fecha_reservacion = "$match[3]-$match[2]-$match[1] $datos[horario]";
               $boleto->operador = $datos["operador"];
               $boleto->placas = $datos["placa"];
               $boleto->reserva_puerto = $datos['puerto'];
               $boleto->reserva_horario = $datos['horario'];
          } else {
               $boleto->pax_extra = 0;
          }

	        preg_match("@(\d+)/(\d+)/(\d+)@", $datos["fecha"], $match);
          $fecha_reservacion = "$match[3]-$match[2]-$match[1] $datos[horario]";
          $reserva_puerto = $datos['puerto'];
          $reserva_horario =  $datos['horario'];
          $boleto->cliente_id = $cliente_id;
          $boleto->catbol_id = $id;
          $boleto->nombre = $categoria->nombre;
          $boleto->catbol_nom = $categoria->nombre;
          $boleto->precio = ($categoria->$precio+$categoria->$iva+$categoria->$tup);
          $boleto->iva = $categoria->$iva;
          $boleto->tup = $categoria->$tup;
          $boleto->origen = $origen;
          $boleto->tipo_operacion = $tipo_operacion;
          $boleto->id_operacion = $id_operacion;
          $boleto->fecha_reservacion = $fecha_reservacion;
          $boleto->reserva_puerto = $reserva_puerto;
          $boleto->reserva_horario = $reserva_horario;
          $boleto->puerto_id = $puerto_id;
          $boleto->pcnt_iva = $puerto->pcnt_iva;
          if($cliente_id != 0)
          {
            $boleto->pcnt_desc = $cliente->descto;
          }
          $boleto->base_categoria = $categoria->$precio;
          $boleto->base_precio = $categoria->$precio;
          $boleto->base_pax_extra = $pax_extra_cat->$precio;
          $boleto->base_exced_long = $exced_long_cat->$precio;

          switch ($origen) {
            case 'conektacard':
            $boleto->fact_tipopago = 'T.CREDITO';
            $boleto->tipo_pago = 1;
            $boleto->formapago = "28";
            break;

            case 'PayPal':
            $boleto->fact_tipopago = 'T.CREDITO';
            $boleto->tipo_pago = 1;
            $boleto->formapago = "28";
            break;

            case 'conektaspei':
            $boleto->fact_tipopago = 'TRANSFERENCIA';
            $boleto->tipo_pago = 0;
            $boleto->formapago = "03";
            break;

            case 'conektaoxxo':
            $boleto->fact_tipopago = 'CONTADO';
            $boleto->tipo_pago = 0;
            $boleto->formapago = "01";
            break;

            default:
            break;
          }

          $boleto->save();

          if(!$ticketfolio) {
              $taquilla = \BoletoWeb::getTerminal();
              $ticketfolio = sprintf("%02d%06d", $taquilla->taquilla_id, $boleto->seguridad);
          }

          $result["folios"][$id][] = $boleto->folioWebCorto;

          $boletos[$id][] = array("folioWeb" => $boleto->folioWeb);
        }
        $result["categorias"][] = array(
          "id"=>$id,
          "nombre"=>$categoria->nombre,
          "precio"=>($categoria->$precio+$categoria->$iva+$categoria->$tup),
          "iva"=>$categoria->$iva,
          "cantidad"=>$cantidad,
"origen"=>$origen
	
        );

        $result["total"] = $result["total"] + (($categoria->$precio+$categoria->$iva+$categoria->$tup)*$cantidad);
        $result["iva"] = $result["iva"] + ($categoria->$iva*$cantidad);

      }

      $result["ticketfolio"]=$ticketfolio;
      $result["subtotal"] = $result["total"]-$result["iva"];

      if($puerto == "Calica") {
        $bcc = \Config::get('cmc.api_generar_email_calica');
      }
      else {
        $bcc = \Config::get('cmc.api_generar_email_czm');
      }

      // send email
      $data = array(
          "result" => $result,
          "datos" => $datos,
          "boletos" => $boletos,
          "email" => trim(\Input::get('email')),
          "bcc" => $bcc,
          "pdf" => false
      );

      // send email
      $data = array(
          "result" => $result,
          "origen" => $origen,
          "datos" => $datos,
          "boletos" => $boletos,
          "email" => trim(\Input::get('email')),
          "bcc" => $bcc,
          "pdf" => false
      );

      \Mail::send('emails.generados', $data, function($message) use ($data) {
        $data["pdf"] = true;
        $pdf = \PDF::loadView('emails.generados', $data);

        $message->from('noreply@transcaribe.net', 'Noreply Transcaribe')
          ->to($data["email"])
          ->bcc($data["bcc"])
          ->subject('TEST Venta Web Transcaribe TEST')
          ->attachData($pdf->stream(), "VENTA_WEB_TRANSCARIBE.pdf");
      });

      return $result;
    });
  }

  function generar_prepago()
  {
    return \DB::transaction(function() {
      $params = ["cliente_id",
      "tipo_operacion",
      "origen",
      "id_operacion",
      "categorias",
      "datos",
      "precio_total",
      "puerto_id"];

      foreach($params as $param) {
        if(!\Input::has($param)) {
          return array(
            "errors"=>array(
              "title"=>"Parametros incompletos",
              "detail"=>"El parametro: '" . $param . "' es requerido",
              "status" => 7
            )
          );
        }
      }

      $params2 = ["pax"];

      foreach($params2 as $param) {
        if(!array_key_exists($param, \Input::get("datos"))) {
          return array(
            "errors"=>array(
              "title"=>"Parametros incompletos",
              "detail"=>"El parametro:  datos[" . $param . "] es requerido",
              "status" => 7
            )
          );
        }
      }

      $cliente_id = \Input::get("cliente_id");
      $tipo_operacion = \Input::get('tipo_operacion');
      $origen = \Input::get('origen');
      $id_operacion = \Input::get('id_operacion');
      $categorias  = \Input::get("categorias");
      $datos = \Input::get("datos");
      $precio_total = \Input::get("precio_total");
      $grupo = \BoletoWeb::generaGrupoId();
      //$puerto_id = intval(\Input::get('puerto_id', 3));
      $puerto_id = intval(\Input::get('puerto_id'));
      $precio = "precio${puerto_id}_1_1";


      $iva = "iva${puerto_id}_1";
      $tup = "tup${puerto_id}_1";

      if(trim($id_operacion) != "") {
        $conn = \DB::connection("local");
        $boletos_id_operacion = $conn->table("casetaVENTAWEB")
          ->select(\DB::raw("COUNT(boleto_id) as total"))
          ->where("id_operacion", $id_operacion)
          ->first();

        if($boletos_id_operacion->total > 0) {
          return array(
            "result" => "error",
            "detail" => "Ya se generaron boletos con el id_operacion"
          );
        }
      }

      $boletos = array();

      $ticketfolio = null;
      \Log::info("VentaWeb", $_POST);

      $server = \DB::connection("server");
      $pax_extra_cat = $server->table('cmc_catboleto')
           ->where('nombre', 'Pax_extra')
           ->first();
      $exced_long_cat = $server->table('cmc_catboleto')
         ->where('nombre', 'Excedente_Longitud')
         ->first();
       $cliente = $server->table("cmc_cliente")
         ->where("cliente_id", $cliente_id)
         ->first();
       $puerto = $server->table("cmc_puerto")
         ->where("puerto_id", $puerto_id)
         ->first();

      if(isset($datos["pax"]))
      {
          $categorias[$pax_extra_cat->catbol_id] = $datos["pax"];
      }

      $result = array(
        "categorias"=>array(),
        "folios"=>array(),
      );

      $result["total"] = 0;
      $result["iva"] = 0;

      foreach($categorias as $id=>$cantidad)
      {
        $categoria = \DB::connection("server")
          ->table("cmc_catboleto")
          ->where("catbol_id", $id)
          ->first();

        $multiplicador = 1;
        if($categoria->nombre == "Excedente_Longitud") {
          $cantidad = 1;
          $multiplicador = $cantidad;
        }

        for($f=0; $f<$cantidad; $f++) {
          $boleto = new \BoletoWeb;
          if(isset($datos["nombres"]) && isset($datos["nombres"][$categoria->catbol_id]) &&count($datos["nombres"][$categoria->catbol_id])>0) {
              $nombre = array_shift($datos["nombres"][$categoria->catbol_id]);
          }
          else {
            $nombre = "Sin nombre";
          }
          $boleto->grupo_id = $grupo;
          $boleto->pax_extra = 0;
          $boleto->cliente_id = $cliente_id;
          $boleto->catbol_id = $id;
          $boleto->nombre = $categoria->nombre;
          $boleto->catbol_nom = $categoria->nombre;
          $boleto->precio = ($categoria->$precio+$categoria->$iva+$categoria->$tup);
          $boleto->iva = $categoria->$iva;
          $boleto->tup = $categoria->$tup;
          $boleto->origen = $origen;
          $boleto->tipo_operacion = $tipo_operacion;
          $boleto->id_operacion = $id_operacion;
          $boleto->puerto_id = $puerto_id;
          $boleto->pcnt_iva = $puerto->pcnt_iva;
          if($cliente_id != 0)
          {
            $boleto->pcnt_desc = $cliente->descto;
          }
          $boleto->base_categoria = $categoria->$precio;
          $boleto->base_precio = $categoria->$precio;
          $boleto->base_pax_extra = $pax_extra_cat->$precio;
          $boleto->base_exced_long = $exced_long_cat->$precio;

          switch ($origen) {
            case 'conektacard':
            $boleto->fact_tipopago = 'T.CREDITO';
            $boleto->tipo_pago = 1;
            $boleto->formapago = "28";
            break;

            case 'PayPal':
            $boleto->fact_tipopago = 'T.CREDITO';
            $boleto->tipo_pago = 1;
            $boleto->formapago = "28";
            break;

            case 'conektaspei':
            $boleto->fact_tipopago = 'TRANSFERENCIA';
            $boleto->tipo_pago = 0;
            $boleto->formapago = "03";
            break;

            case 'conektaoxxo':
            $boleto->fact_tipopago = 'CONTADO';
            $boleto->tipo_pago = 0;
            $boleto->formapago = "01";
            break;

            default:
            break;
          }
          
          $boleto->save();

          if(!$ticketfolio) {
              $taquilla = \BoletoWeb::getTerminal();
              $ticketfolio = sprintf("%02d%06d", $taquilla->taquilla_id, $boleto->seguridad);
          }

          $result["folios"][$id][] = $boleto->folioWebCorto;

          $boletos[$id][] = array("folioWeb" => $boleto->folioWeb);
        }
        $result["categorias"][] = array(
          "id"=>$id,
          "nombre"=>$categoria->nombre,
          "precio"=>($categoria->$precio+$categoria->$iva+$categoria->$tup),
          "iva"=>$categoria->$iva,
          "cantidad"=>$cantidad
        );

        $result["total"] = $result["total"] + (($categoria->$precio+$categoria->$iva+$categoria->$tup)*$cantidad);
        $result["iva"] = $result["iva"] + ($categoria->$iva*$cantidad);

      }

      $result["ticketfolio"]=$ticketfolio;
      $result["subtotal"] = $result["total"]-$result["iva"];

      if($puerto == "Calica") {
        $bcc = \Config::get('cmc.api_generar_email_calica');
      }
      else {
        $bcc = \Config::get('cmc.api_generar_email_czm');
      }

      // send email
      $data = array(
          "result" => $result,
          "datos" => $datos,
          "boletos" => $boletos,
          "email" => trim(\Input::get('email')),
          "bcc" => $bcc,
          "pdf" => false
      );

      // send email
      $data = array(
          "result" => $result,
          "origen" => $origen,
          "datos" => $datos,
          "boletos" => $boletos,
          "email" => trim(\Input::get('email')),
          "bcc" => $bcc,
          "pdf" => false
      );

      \Mail::send('emails.generados', $data, function($message) use ($data) {
        $data["pdf"] = true;
        $pdf = \PDF::loadView('emails.generados', $data);

        $message->from('noreply@transcaribe.net', 'Noreply Transcaribe')
          ->to($data["email"])
          ->bcc($data["bcc"])
          ->subject('Venta Web Transcaribe')
          ->attachData($pdf->stream(), "VENTA_WEB_TRANSCARIBE.pdf");
      });

      return $result;
    });
  }

  function marcas()
  {
    $conn = \DB::connection("server");

    $marcas = $conn->table("cmc_marcas")
        ->select("id", "nombre")
        ->orderBy("nombre")
        ->get();

    return $marcas;
  }

  function modelos($id)
  {
    $conn = \DB::connection("server");

    $marcas = $conn->table("cmc_modelos")
        ->select("id", "nombre")
        ->where("marca_id", $id)
        ->orderBy("nombre")
        ->get();

    return $marcas;
  }

  function oxxo_conekta($oxxo_id)
  {
    $conn = \DB::connection("local");

    $pagos_oxxo = $conn->table("oxxo_conekta")
        ->select("id", "descripcion")
        ->where("id", $oxxo_id)
        ->first();

    if(!$pagos_oxxo)
    {
      return array(
        "errors"=>array(
          "detail"=>"No se encontro el valor.",
          "status" => 0
        )
      );
    }

    $data = array(
      "id"=>$oxxo_id,
      "descripcion"=>$pagos_oxxo->descripcion,
    );
    return $data;
  }

  function guarda_oxxo_conekta()
  {
    $descripcion = \Input::get('descripcion', '');

    if($descripcion == '')
    {
      return array(
        "errors"=>array(
          "title"=>"Descripción invalida",
          "detail"=>"No se ha proporcionado una descripción",
          "status" => 0
        )
      );
    }

    $oxxoConekta = new \OxxoConekta;
    $oxxoConekta->descripcion = $descripcion;
    $oxxoConekta->save();

    return array(
      "result"=>array(
        "title"=>"Descripción guardada",
        "status" => 1
      )
    );
  }


  function validaDisponibilidad($catbol_id,
    $puerto_id,
    $fecha_reservacion,
    $cantidad)
  {
    $conn = \DB::connection("server");
    $disponibilidad = $conn->table("cmc_catboleto")
        ->select(\DB::raw("disponibilidad as total"))
        ->where("catbol_id", $catbol_id)
        ->first();

    $conn = \DB::connection("local");
    $vendidos = $conn->table("casetaVENTAWEB")
        ->select(\DB::raw("COUNT(boleto_id) as total"))
        ->where("catbol_id", $catbol_id)
        ->where("puerto_id", $puerto_id)
        ->where("fecha_reservacion", $fecha_reservacion)
        ->first();

    if(($disponibilidad->total - $vendidos->total) >= $cantidad) {
      return true;
    }
    else {
      return false;
    }
  }


  function verificaDisponibilidad()
  {
    $params = ["catbol_id",
    "puerto_id",
    "fecha_reservacion",
    "cantidad"];

    foreach($params as $param) {
      if(!\Input::has($param)) {
        return array(
          "errors"=>array(
            "title"=>"Parametros incompletos",
            "detail"=>"El parametro: '" . $param . "' es requerido",
            "status" => 7
          )
        );
      }
    }

    $catbol_id = \Input::get('catbol_id');
    $puerto_id = \Input::get('puerto_id');
    $fecha_reservacion = \Input::get('fecha_reservacion');
    $cantidad = \Input::get('cantidad');

    if($this->validaDisponibilidad($catbol_id,
      $puerto_id,
      $fecha_reservacion,
      $cantidad)) {
      return array(
        "disponibilidad" => true
      );
    }
    else {
      return array(
        "disponibilidad" => false
      );
    }
  }
}
