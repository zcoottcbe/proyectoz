@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
          <div class="card">
            <div class="card-header">Prepago</div>
            <div class="body">
              <table class="table">
                <tr>
                  <td><strong>Código:</strong></td>
                  <td>{{ $prepago->codigo }}</td>
                </tr>
                <tr>
                  <td><strong>Usado en:</strong></td>
                  <td>{{ $usado["lugar"] }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha:</strong></td>
                    <td>{{ $usado["fecha"] }}</td>
                </tr>
              </table>
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
                <a href="{{ url('/') }}" class="btn btn-danger">Cancelar</a>
              </div><!-- float -->
            </div><!-- card-body -->
          </div><!-- card -->
        </div><!-- col -->

    </div><!-- row -->
</div>
@endsection


