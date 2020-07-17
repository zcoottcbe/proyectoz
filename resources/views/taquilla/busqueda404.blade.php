@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            No se encontró ningun resultado.
        </div>
        <div class="col">
        </div>
        <div class="col">
          <div class="card">
            <div class="card-header">Sesión</div>

            <div class="card-body">
              @include('sesion.resumen')
              <div class="float-right">
                <a href="{{ url("/") }}" class="btn btn-danger">Cancelar</a>

              </div><!-- float -->
            </div><!-- card-body -->
          </div><!-- card -->
        </div><!-- col -->

    </div><!-- row -->
</div>
@endsection


