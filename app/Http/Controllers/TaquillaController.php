<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Boleto;
use App\Cliente;

class TaquillaController extends Controller
{
    public function asignar() {
      $taquillas = Auth::user()->taquillas();
      return view('taquilla.asignar', compact('taquillas'));
    }

    public function buscar(Request $request) {
        return view('taquilla.busqueda404');
    }
}
