<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as WebSession;
use Illuminate\Support\Facades\Validator;
use App\Session;
use App\Taquilla;
use App\Categoria;
use App\MetodoDePago;
use App\UsoCFDI;
use App\TipoDePago;
use App\FormaDePago;
use App\Moneda;
//zlcb
use Mail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

//zlcb
class SesionController extends Controller
{
    /**
     * create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function taquilla(Taquilla $taquilla) {
      Session::taquilla($taquilla);
      return redirect('/categorias');
    }

    public function moneda(Moneda $moneda) {
      Session::moneda($moneda);
      return back();
    }


    public function limpiar() {
      Session::clear();
      return redirect('/');
    }

    public function revisar() {
      $lista = Session::list();
      $metodosDePago = MetodoDePago::all();
      $formasDePago = FormaDePago::all();
      $usosCFDI = UsoCFDI::all();
      $tiposDePago = TipoDePago::session();

      return view('sesion.lista', compact('lista',
        'metodosDePago', 'formasDePago', 'usosCFDI', 'tiposDePago'));
    }

    public function guardar(Request $request) {
       $guardados = collect(Session::save(
        Auth::user(),
        MetodoDePago::find($request->input("metodo_pago_id")),
        FormaDePago::find($request->input("forma_pago_id")),
        UsoCFDI::find($request->input("uso_cfdi_id")),
        TipoDePago::find($request->input("tipo_pago_codigo")),
        $request->input("prepago"),
        $request->input("roundtrip")
      ));


      
      //echo '<script>alert("paso 2")</script>';
      Session::clear();
      $taquilla = Session::taquilla();
      //echo '<script>alert("paso 3")</script>';
      $boletos = $guardados->map(function($id) use ($taquilla) {
        return $taquilla->boletoQuery()->find($id);
      })->filter(function($boleto) {
          return redirect('/categorias');
      });
//echo '<script>alert("paso 4")</script>';
      
      $roundtrip = $request->input("roundtrip"); //Valida si se seleccionó el roundtrip
      $email_user = $request->input("email_user"); //Valida Correo del usuario
      $tipo_pago = $request->input("tipo_pago_codigo"); //Valida si es un prepago
      /* zlcb */
       switch ($request->input('action')) {
        case 'email':
          echo '<br>TIPO PAGO: '.$request->input("tipo_pago_codigo");
          echo '<script>alert("EMAIL")</script>';
          
          if(!env("NEOPOS_VEHICULOS")) { //GTR falta agregar nueva plantilla
            if($boletos->count()>0) {
              if ($roundtrip == 0){ //Muestra los códigos de los boletos generados
                  if ($tipo_pago=='PREPAGO'){
                    $request->session()->flash('alert-info',
                            view("codigos.resumenprepagos", compact('boletos'))->render()
                    );
                  }
                  else{
                    $request->session()->flash('alert-info',
                            view("codigos.resumen", compact('boletos'))->render()
                    );
                    $data = array(
                      "type"=>"email",
                      "pdf" => false,
                      "boletos"=>$boletos,
                      "email_user"=>$email_user
                    );

                     \Mail::send('emails.codigos', $data, function($message) use ($data) {
                      $data["pdf"] = true;
                      $pdf = \PDF::loadView('emails.generadosgtr', $data);

                      $message->from('zcoot@transcaribe.mx', 'Zuriana');
                      $message->to($data["email_user"]);
                      $message->cc('alias4@transcaribe.mx');
                        //->cc('ccastillo@winjet.mx')->cc('mmunoz@transcaribe.net')->cc('zuriana@gmail.com');
                      $message->subject('Envío de códigos Winjet');
                      $message->attachData($pdf->stream(), "CODIGOS_WINJET.pdf");
                    });
                  }

                  
              }
              else{
                echo '<script>alert("Entra aqui")</script>';
                $request->session()->flash('alert-info',
                    view("prepago.codigosGenerados", compact('boletos'))->render()
                );
              }
            };
          }
          else{
            if($boletos->count()>0) {
              if ($roundtrip == 0){ //Muestra los códigos de los boletos generados
                $request->session()->flash('alert-info',
                          view("codigos.resumen", compact('boletos'))->render()
                ); 

                  $data = array(
                    "type"=>"email",
                    "pdf" => false,
                    "boletos"=>$boletos,
                    "email_user"=>$email_user
                  );

                   \Mail::send('emails.codigos', $data, function($message) use ($data) {
                    $data["pdf"] = true;
                    $pdf = \PDF::loadView('emails.generados', $data);

                    $message->from('zcoot@transcaribe.mx', 'Zuriana');
                    $message->to($data["email_user"]);
                    $message->cc('alias4@transcaribe.mx');
                      //->cc('ccastillo@winjet.mx')->cc('mmunoz@transcaribe.net')->cc('zuriana@gmail.com');
                    $message->subject('Envío de códigos Normales');
                    $message->attachData($pdf->stream(), "CODIGOS_TCBE.pdf");
                  });
              }
              else{
                $request->session()->flash('alert-info',
                          view("prepago.codigosGenerados", compact('boletos'))->render()
                );
              }
            };
          } //Termina else

          //Validar si el usuario escribió el correo del cliente

             
            return redirect('/categorias');
         

        break;

        case 'print':
          if(!env("NEOPOS_VEHICULOS")){ // Entra si es GTR neopos = false
            //Validar si es venta web

            //echo '<script>alert("neopos es false")</script>';
            //echo '<h1>Imprimir</h1>';
            //var_dump($boletos);
            //echo 'IMPRIMIR '. $boletos->boleto_id;
            foreach ($boletos as $p) {
              $boleto = $p["boleto_id"];
              echo '<br>Boleto: '.$boleto;
              //return redirect()->action('SesionController@imprimirboletos', ['boleto_id' => $boleto]);
            }



          }
          else{ // Entra si es TCBE neopos = true
            echo '<script>alert("false")</script>';

          }
          
        
        //$taquilla = Session::taquilla();

        
       
        /*$datosbol = DB::table('caseta'.$taquilla->nombre)->where('boleto_id', $boleto_id)->get();

        foreach ($datosbol as $key => $value) {
          echo $value.'<br><br>';
        }
*/
        /*$grupoid = $datosbol->grupo_id;
        $conteo = DB::table('caseta'.$taquilla->nombre)->where('grupo_id', $grupoid)->count();*/
        
/*
        echo '<html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="_token" content="{{ csrf_token() }}" />
            <title>Prueba</title>
            <script type="text/javascript" src="../qz/dependencies/rsvp-3.1.0.min.js"></script>
            <script type="text/javascript" src="../qz/dependencies/sha-256.min.js"></script>
            <script type="text/javascript" src="../qz/qz-tray.js"></script>
            <script src="../kendo/js/jquery.min.js" type="text/javascript"></script>
            <script src="../kendo/js/kendo.all.min.js" type="text/javascript"></script>
            <script src="../kendo/js/kendo.all.min.js" type="text/javascript"></script>
            <script src="../kendo/js/messages/kendo.messages.es-MX.min.js"></script>
            <link rel="stylesheet" href="../kendo/styles/kendo.common.min.css" />
            <link rel="stylesheet" href="../kendo/styles/kendo.default.min.css" />
            <link rel="stylesheet" href="../kendo/styles/kendo.default.mobile.min.css" />
            

        ';
          echo '
           <script type="text/javascript">
            $( document ).ready(function() {
                var popupNotification = $(\'#popupNotification\').kendoNotification({
                  autoHideAfter: 5000,
                  button: false,
                  hideOnClick: false,
                  position: {
                    pinned: true,
                    top: 20,
                    left: null,
                    bottom: null,
                    right: 20
                  }
                }).data(\'kendoNotification\');
                popupNotification.hide().show(\' Prueba...\', \'info\');
            });

          </script>';


          echo '
            <script type="text/javascript">
                
               acivarconn = function(boletoId) {
                  alert("se supone activa conexion");
                if (!qz.websocket.isActive()) {
                    qz.websocket.connect().then(function() {
                  
                      if (!qz.websocket.isActive()) {
                        popupNotification.hide().show(\' No existe una conexión con QZ Tray o fue cerrada\', \'error\');
                      }
                      else {
                        var config = qz.configs.create(\'impresora_boletos\');
                        var data = [\'prueba\'];
                        return qz.print(config, data);
                      }
                    }).catch(handleConnectionError);
                    
                  } else {
                    popupNotification.hide().show(\' Conexion activa\', \'info\');
                  }
                }


                startConnection = function(boletoId) {
                  

                  qz.websocket.connect().then(function() {
                      
                      if (!qz.websocket.isActive()) {
                        popupNotification.hide().show(\' No existe una conexión con QZ Tray o fue cerrada\', \'error\');
                      }
                      else {
                        var config = qz.configs.create(\'impresora_boletos\');
                        var data = [\'prueba\'];
                        return qz.print(config, data);
                      }
                      
                    }).catch(handleConnectionError);


                  
                }
            </script>';

        $conteo = 0;



        foreach ($boletos as $boleto)
          {
            $conteo++;

            echo '<h1>'.$boleto->boleto_id.'<br>Conteo: '.$conteo;
            if ($conteo==1){
              echo 'Entra aqui';
              echo '<script>
                      acivarconn();
                      startConnection('.$boleto->boleto_id.');
                    </script>';
            }
            else{
              echo 'Entra aqui si hay más de 1 elemento';
              echo '<script>
              alert("manda a llamar a startConnection");
                      startConnection('.$boleto->boleto_id.');
                    </script>';
            }



          }//Termina el foreach



echo ' </head>
        <body>popupNotification
        <span id="popupNotification"></span>
        </body>';

          
*/
             

            /*echo 'IMPRIMIR '. $boletos->boleto_id;
            foreach ($boletos as $p) {
              $boleto = '<br>'.$p["boleto_id"];
              return redirect()->action('SesionController@imprimirboletos', ['boleto_id' => $boleto]);
            }

           return view("imprimir.transaccion",compact('boletos'));*/
          
          

          break;
        }




    }


    public function imprimirboletos(Request $request) {
      $boleto = $request->input('boleto_id');
      return '
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>Prueba</title>
    <script type="text/javascript" src="../qz/dependencies/rsvp-3.1.0.min.js"></script>
    <script type="text/javascript" src="../qz/dependencies/sha-256.min.js"></script>
    <script type="text/javascript" src="../qz/qz-tray.js"></script>
    <script src="../kendo/js/jquery.min.js" type="text/javascript"></script>
    <script src="../kendo/js/kendo.all.min.js" type="text/javascript"></script>
    <script src="../kendo/js/kendo.all.min.js" type="text/javascript"></script>
    <script src="../kendo/js/messages/kendo.messages.es-MX.min.js"></script>
    <link rel="stylesheet" href="../kendo/styles/kendo.common.min.css" />
    <link rel="stylesheet" href="../kendo/styles/kendo.default.min.css" />
    <link rel="stylesheet" href="../kendo/styles/kendo.default.mobile.min.css" />
  
 </head>
<body>
<span id="popupNotification"></span>
</body>

<script type="text/javascript">

$(document).ready(function(){
  var popupNotification = $(\'#popupNotification\').kendoNotification({
    autoHideAfter: 5000,
    button: false,
    hideOnClick: false,
    position: {
      pinned: true,
      top: 20,
      left: null,
      bottom: null,
      right: 20
    }
  }).data(\'kendoNotification\');


  startConnection = function() {
    if (!qz.websocket.isActive()) {
      popupNotification.hide().show(\' Conectando con QZ Tray...\', \'info\');
      qz.websocket.connect().then(function() {
        popupNotification.hide().show(\' Coneccción establecida con QZ Tray\', \'success\');
        imprimirBoleto('.$boleto.');
      }).catch(handleConnectionError);
    } else {
      popupNotification.hide().show(\' Conección activa con QZ Tray\', \'info\');
    }
  }

   handleConnectionError = function() {
    popupNotification.hide().show(\'Error de conección con QZ Tray\', \'error\');

    if (err.target != undefined) {
      if (err.target.readyState >= 2) {
        popupNotification.hide().show(\' La conección con QZ Tray fue cerrada\', \'error\');
      } else {
        popupNotification.hide().show(\' Ocurrio un error de conección con QZ Tray: \' + err, \'error\');
      }
    } else {
      popupNotification.hide().show(err, \'error\');
    }   
  }

  imprimirBoleto = function(boletoId) {
    if (!qz.websocket.isActive()) {
      popupNotification.hide().show(\' No existe una conección con QZ Tray o fue cerrada\', \'error\');
    }
    else {
      $.ajax({
        url: \'/sesion/redimirboletos\',
        type: \'GET\',
        data: {
          boleto_id: boletoId
        },
        success: function(result) {
          if(result.result == \'success\') {

            var config = qz.configs.create(\'impresora_boletos\');

            $.each(result.detail, function(index, value) {
              result.detail[index] = result.detail[index].replace(\'\\n\',\'\n\');
            });

            qz.print(config, result.detail).then(function() {
              popupNotification.hide().show(\' Se mando imprimir el boleto #\' + boletoId, \'info\');
                 location.href="../categorias";

            });

          }
          else {
            popupNotification.hide().show(\' No se pudo imprimir el boleto #\' + boletoId + \': \' + result.detail, \'error\');
          }
        }});
    }
  }
  startConnection();
});
</script>
';

    }

    public function redimirboletos(Request $request) {
      $boleto_id = $request->input('boleto_id');
      $taquilla = Session::taquilla();
      $datosbol = DB::table('caseta'.$taquilla->nombre)->where('boleto_id', $boleto_id)->first();
      $grupoid = $datosbol->grupo_id;

      $conteo = DB::table('caseta'.$taquilla->nombre)->where('grupo_id', $grupoid)->count();
      $datos = DB::table('caseta'.$taquilla->nombre)->where('grupo_id', $grupoid)->first();
      
      //echo 'Antes de entrar';
      
      /*foreach ($datos->boleto_id as $sku) {
        echo $sku;
      } */
      
      $detail = [
        $datos->grupo_id.chr(13),
        $conteo.chr(13),
        'TRANSBORDADORES DEL CARIBE SA DE CV'.chr(13),
        /*'C. 6 NTE # 14 /10 Y 15 AVENIDAS'.chr(10),
        'COL.CENTRO'.chr(10),
        'C.P.77600'.chr(10),
        'RFC.TCA030312II3 TEL.01(987)8727688'.chr(10),
        'TAQUILLA: '.$taquilla->nombre.chr(10),
        'BOLETO_ID: ' . $request->input('boleto_id'),
        'OPERADOR: ' . $datos->operador .chr(10),
        'PASE ABORDAR: ' . $datos->pase_abordar .chr(10),
        'PLACAS: ' . $datos->placas . chr(10),
        'CATEGORIA: ' . $datos->nombre . chr(10),
        'PASAJEROS: ' . $datos->pax_extra . chr(10),
        'EXCEDENTE: ' . $datos->excedente_longitud . chr(10),
        'TOTAL: ' . $datos->preciofact . chr(10),
        'GRACIAS POR VIAJAR CON NOSOTROS'.chr(10),
        'PARA CONSULTAR LAS POLITICAS DE'.chr(10),
        'EMBARQUE VISITA:'.chr(10),*/
        'http://www.transcaribe.net/esp/'.chr(13),
        'politicas-embarque.cfm'.chr(10).chr(10).chr(10).chr(10).chr(10).chr(10)
         ];

      return \Response::json(array(
        'result' => 'success',
        'detail' => $detail
      ));

     
    }
}







