<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Categoria;
use App\Session;
use App\Taquilla;
use App\Moneda;
use App\Orden;

class CategoriaController extends Controller
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

    public function indice() {
      $taquilla_id = session('taquilla_id');

      $categorias = Taquilla::find($taquilla_id)->categorias();

      $lista = Session::list();

      return view('categoria.index', compact('categorias', 'lista'));
    }

    public function buscar() {
      return view('taquilla.buscar');
    }

    public function agregar(Categoria $categoria, $cantidad=1) {
        $servicio = new \App\ServicioPublico($categoria);
        Session::crearOrden($servicio, $cantidad);
        return redirect()->route('orden.editar');
    }

}
