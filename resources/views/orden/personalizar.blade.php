@extends('layouts.app')

@section('content')
<div class="container">
  <form method="post" action="{{ route('orden.personalizar') }}">
    @csrf
    <div class="card">
        <div class="card-header">Datos</div>
        <div class="card-body container">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="text" name="precio" id="precio" value="{{ $precio_mxn["precio"] }}" class="form-control">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="tup">TUP</label>
                    <input type="text" name="tup" id="tup" value="{{ $precio_mxn["tup"] }}" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="precio_usd">Precio USD</label>
                    <input type="text" name="precio_usd" id="precio_usd" value="{{ $precio_usd["precio"] }}" class="form-control">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="tup_usd">TUP USD</label>
                    <input type="text" name="tup_usd" id="tup_usd" value="{{ $precio_usd["tup"] }}" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="info">Información</label>
                    <textarea name="info" id="info" class="form-control"></textarea>
                  </div>
                </div>
              </div>
              <div class="float-right">
                <a href="{{ url("/") }}" class="btn btn-danger"><span class="oi oi-ban" title="Cancelar" aria-hidden="true"></span> Cancelar</a>

                <button type="submit" class="btn btn-primary">
                <span class="oi oi-media-skip-forward" title="Continuar" aria-hidden="true"></span> Continuar
                </button>
              </div>
        </div>
    </div><!-- row -->
  </form>
</div>
@endsection


