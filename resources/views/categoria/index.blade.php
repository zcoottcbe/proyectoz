@extends('layouts.app')

@section('content')
<div class="container">
      <div class="card" style="margin-top: 1em">
        <div class="card-header">Categorias</div>
        <div class="card-body container">
            <div class="row">
                <div class="col-md-3 offset-md-9" style="text-align: right; margin-bottom: 5px;">
                    <input id="cantidadBoletos" value="1" type="number" min="1" max="1000">
                </div>
            </div>
          <div class="row">
      @foreach ($categorias as $categoria)
        <div class="col-md-4">
            <a href="{{ route("categoria.agregar", [$categoria->id]) }}" class="btn btn-block btn-secondary btn-tall btn-categoria" data-url="{{ route("categoria.agregar", [$categoria->id]) }}" onclick="return agregar(this)">{{$categoria->nombre}}</a>
        </div>
      @endforeach
        </div>
        <div class="float-right">
          <a href="{{ route("sesion.limpiar") }}" class="btn btn-secondary"><span class="oi oi-trash" title="Limpiar" aria-hidden="true"></span> Limpiar</a>

          <a href="{{ route("sesion.revisar") }}" class="btn btn-primary"><span class="oi oi-media-skip-forward" title="Continuar" aria-hidden="true"></span> Continuar</a>
        </div><!-- float -->
      </div>
    </div>
    

    <div class="row d-none d-lg-block">
        <div class="col">
            <br>
        @include("sesion._lista")
        </div>
    </div>
</div>
<script>
function agregar(boton) {
    let cantidad = document.getElementById('cantidadBoletos').value;
    boton.href = boton.attributes['data-url'].value+'/'+cantidad;
    return true;
}
</script>

@endsection

