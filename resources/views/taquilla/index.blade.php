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
            <div class="card-header">sesión</div>

            <div class="card-body">
              @include('sesion.resumen')
              <div class="float-right">
                <a href="{{ route("sesion.limpiar") }}" class="btn btn-secondary">limpiar</a>

                <a href="{{ route("sesion.revisar") }}" class="btn btn-primary">continuar</a>
              </div><!-- float -->
            </div><!-- card-body -->
          </div><!-- card -->
        </div><!-- col -->

    </div><!-- row -->

    <hr>
    <div class="row">
      @foreach ($categorias as $categoria)
        <div class="col-md-4">
          <form method="post" action="{{ route('categoria.agregar') }}">
            @csrf
            <input type="hidden" name="categoria_id" value="{{ $categoria->id }}">
            <input type="hidden" name="cantidad" value="1">
            <input type="submit" value="{{ $categoria->nombre }}" class="btn btn-block btn-secondary btn-tall" style="margin-bottom: 5px">
          </form>
        </div>
      @endforeach
    </div>
</div>
@endsection

