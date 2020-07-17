<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Session;
use App\Orden;
use App\Categoria;

class ClienteController extends Controller
{
  function credencialAgregar(Request $request) {
    $credencial = $request->input('credencial');
    $catbol_id = $request->input('catbol_id');
    if(!$credencial) {
      redirect("/");
    }

    $cliente = Cliente::credencial($credencial)
      ->first();

    if(!$cliente) {
      return redirect('/')
        ->with('alert-info', 'No se encontro la credencial');
    }

    $categoria = Categoria::find($catbol_id);
    $cliente->session_catbol_id = $catbol_id;

    Session::crearOrden($cliente->crearServicio($categoria));
    return redirect()->route('orden.editar');
  }

}
