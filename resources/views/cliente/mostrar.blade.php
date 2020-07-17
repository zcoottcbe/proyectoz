@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
          <div class="card">
            <div class="card-header">Información</div>
            <div class="card-body">
              <div><strong>Nombre:</strong></div>
              <div>{{ $cliente->nombre }}</div>
              @if($cliente->inactivo)
                <div><strong>Status:</strong></div>
                <div class="text-danger">{{ $cliente->inactivo }}</div>
              @endif
              @if($cliente->observaciones_taquilla)
              <div><strong>Observaciones:</strong></div>
              <div>{{ $cliente->observaciones_taquilla }}</div>
              @endif
            </div>
          </div>
        </div>
        <div class="col">
        </div>
        <div class="col">
          <div class="card">
            <div class="card-header">Sesión</div>

            <div class="card-body">
              @include('sesion.resumen')
              <div class="float-right">
                <a href="{{ route("sesion.limpiar") }}" class="btn btn-secondary">Limpiar</a>
                <span onclick="window.history.back()" class="btn btn-danger">Cancelar</span>
              </div><!-- float -->
            </div><!-- card-body -->
          </div><!-- card -->
        </div><!-- col -->

    </div><!-- row -->

    <div class="card" style="margin-top: 1em">
      <div class="card-header">Categorias del cliente</div>
      <div class="card-body container"><div class="row">
      @foreach ($categorias as $categoria)
        <div class="col-md-4">
          <form method="post" action="{{ route('cliente.credencialAgregar') }}">
            @csrf
            <input type="hidden" name="credencial" value="{{ $cliente->credencial }}">
            <input type="hidden" name="catbol_id" value="{{ $categoria->id }}">
            <input type="submit" value="{{ $categoria->nombre }}" class="btn btn-block btn-secondary" style="margin-bottom: 5px">
          </form>
        </div>
      @endforeach
      </div></div>
</div>
@endsection


