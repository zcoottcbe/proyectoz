@extends('layouts.app')

@section('content')
<form action="{{ route('vehiculo.guardar') }}" method="post">
    @csrf
<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="card">
        <div class="card-header">Datos</div>
        <div class="card-body container">
        <div class="row">
            <div class="col-md-6">
                <label for="placas"><strong>Placas</strong></label>
                <!--<input type="text" class="form-control"
                    value="{{ @$datos["placas"] }}"
                    id="placas" name="placas" autofocus="autofocus"> zlcb-->
                    <input type="text" class="form-control"
                    value="ABC1234"
                    id="placas" name="placas" autofocus="autofocus">
            </div>
            <div class="col-md-6">
                <label for="operador"><strong>Operador</strong></label>
                <!--<input type="text" class="form-control"
                    value="{{ @$datos["operador"] }}"
                    id="operador" name="operador">  zlcb-->
                    <input type="text" class="form-control"
                    value="Zuriana"
                    id="operador" name="operador">
            </div>
            <div class="col-md-6">
                <label for="pase_abordar"><strong>Pase de abordar</strong></label>
                <!--<input type="text" class="form-control"
                    value="{{ @$datos["pase_abordar"] }}"
                    id="pase_abordar" name="pase_abordar">  zlcb-->
                    <input type="text" class="form-control"
                    value="12345"
                    id="pase_abordar" name="pase_abordar">
            </div>
            <div class="col-md-6">
                <label for="pax_extra">Pasajeros</label>
                <input type="text" class="form-control"
                    value="{{ @$datos["pax_extra"] }}"
                    id="pax_extra" name="pax_extra">
            </div>
            <div class="col-md-6">
                <label for="largo">Largo</label>
                <input type="text" class="form-control" 
                    value="{{ @$datos["largo"] }}"
                    id="largo" name="largo">
            </div>
            <div class="col-md-6">
                <label for="excedente_longitud">Excedente de longitud</label>
                <input type="text" class="form-control" 
                    value="{{ @$datos["excedente_longitud"] }}"
                    id="excedente_longitud" name="excedente_longitud">
            </div>
            <div class="col-md-6">
                <label for="extra">Empresa</label>
                <input type="text" class="form-control"
                    value="{{ @$datos["extra"] }}"
                    id="extra" name="extra">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ancho_extra" style="display: block">Ancho extra</label>
                    <input type="hidden" value="0" 
                        name="ancho_extra">
                    <input type="checkbox" value="1" 
                        @if (@$datos["ancho_extra"]) checked @endif
                        id="ancho_extra" name="ancho_extra">
                    <label class="form-check-label" for="ancho_extra"> Si</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="descuento">Descuento</label>
                    <input type="text" class="form-control"
                        value="{{ @$datos["descuento"] }}"
                        id="descuento" name="descuento">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="info">Observaciones</label>
                    <textarea class="form-control"
                        id="info" name="info">{{ @$datos["info"] }}</textarea>
                </div>
            </div>
        </div>
      </div>
    </div>

      <div class="card" style="margin-top: 1em">
        <div class="card-header">Categorias</div>
        <div class="card-body container">
          <div class="row">
      @foreach ($categorias as $categoria)
        <div class="col-md-4">
            <div data-toggle="buttons">
            <label class="btn btn-block btn-secondary btn-tall">
                <input type="radio" 
                    name="catbol_id" 
                    {{ ($orden->servicio->categoria->id==$categoria->id?
                        "checked": "") }}
                    value="{{ $categoria->id }}"
                ><br>{{$categoria->nombre}}
            </label>
            </div>
        </div>
      @endforeach
        </div>
        <div class="float-right">
            <a href="{{ url("/") }}" class="btn btn-danger"><span class="oi oi-ban" title="Cancelar" aria-hidden="true"></span> Cancelar</a>

            <button type="submit" class="btn btn-primary">
            <span class="oi oi-media-skip-forward" title="Continuar" aria-hidden="true"></span> Continuar
            </button>
        </div>
      </div>
    </div>
</div>
</form>
@endsection


