@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
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

    <br>
        <table class="table table-bordered table-striped">
          <tr>
            <th>Nombre</th>
            <th>Descuento</th>
            <th>RFC</th>
            <th>Status</th>
          </tr>
          @foreach ($clientes as $cliente)
            <tr>
              <td>
                <form method="post" action="{{ route("taquilla.buscar") }}">
                  @csrf
                  <input type="hidden" name="buscar" value="{{ $cliente->credencial }}">
                  <button class="btn btn-primary"><span class="oi oi-plus" title="Agregar" aria-hidden="true"></span></button>
                  {{ $cliente->nombre }}
              </form>
              </td>
              <td align="right">{{ $cliente->descto }}%</td>
              <td>{{ $cliente->rfc }}</td>
              <td class="text-danger">{{ $cliente->inactivo }}</td>
            </tr>
          @endforeach
</div>
@endsection



