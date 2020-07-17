<?php

namespace App;

class ServicioCliente extends ServicioPublico 
{
    function __construct(Cliente $cliente, $catbol_id=null) {
        $this->session_catbol_id = $catbol_id?: $cliente->catbol_id;

        $categoria = Categoria::find($this->session_catbol_id);
        parent::__construct($categoria);

        $this->cliente = $cliente;
    }

    public function nombre() {
        return $this->cliente->nombre;
    }

    public function columnaNombre() {
        return $this->cliente->nombre;
    }

    public function columnaInfo() {
        return $this->cliente->info;
    }

    public function categorias() {
        return $this->cliente->categorias();
    }
}
