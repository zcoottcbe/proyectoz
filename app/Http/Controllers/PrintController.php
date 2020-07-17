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

require '/home/vagrant/code/neopos/escpos-php/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\FilePrintConnector;




use Mike42\Escpos\Printer;
//zlcb
class PrintController extends Controller
{
    public function verRegistros()
    {
      
      $connector = new FilePrintConnector("impresora_boletos");
$printer = new Printer($connector);

/* Print some bold text */
$printer -> setEmphasis(true);
$printer -> text("FOO CORP Ltd.\n");
$printer -> setEmphasis(false);
$printer -> feed();
$printer -> text("Receipt for whatever\n");
$printer -> feed(4);

/* Bar-code at the end */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> barcode("987654321");
$printer -> cut();



      $nombreImpresora = "impresora_boletos";
      //$connector = new WindowsPrintConnector($nombreImpresora);
      $connector = new FilePrintConnector("php://stdout");
      $impresora = new Printer($connector);

      $impresora -> initialize();

      $impresora->text("Imprimiendo\n");
      $impresora->text("ticket\n");
$impresora->text("desde\n");
$impresora->text("Laravel\n");
$impresora->setTextSize(1, 1);
$impresora->text("https://parzibyte.me");
$impresora->feed(5);
      echo 'Crea impresora';
      $impresora -> cut();
      $impresora->close();
      echo '<br>cierra impresora';
/*$impresora = new Printer($connector);
$impresora->text("Imprimiendo\n");
$impresora->close();
$impresora->setJustification(Printer::JUSTIFY_CENTER);
$impresora->setTextSize(2, 2);

$impresora->text("ticket\n");
$impresora->text("desde\n");
$impresora->text("Laravel\n");
$impresora->setTextSize(1, 1);
$impresora->text("https://parzibyte.me");
$impresora->feed(5);

*/
    }
   

/*    
  $boleto_id = $request->input('boleto_id');
      $taquilla = Session::taquilla();
      $datosbol = DB::table('caseta'.$taquilla->nombre)->where('boleto_id', $boleto_id)->first();
      $grupoid = $datosbol->grupo_id;

      $conteo = DB::table('caseta'.$taquilla->nombre)->where('grupo_id', $grupoid)->count();
      $datos = DB::table('caseta'.$taquilla->nombre)->where('grupo_id', $grupoid)->first();*/
   
}







