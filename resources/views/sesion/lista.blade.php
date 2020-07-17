@extends('layouts.app')

@section('content')



 <form method="post" action="{{ route("sesion.guardar") }}"> 

<!--<form method="get" action="{{ route("print.verregistros") }}">-->
  @csrf
<div class="container">
    {{--<div class="row justify-content-center">
        <div class="col">
            <span id="notification"></span>
            @include("sesion/_qzlist")
        </div>
    </div>--}}


    <div class="row justify-content-center">
      

      <div class="col">
        <div class="card">
          <div class="card-header">Tipo de pago</div>
            <div class="card-body">
              <div class="row">
                @foreach ($tiposDePago as $tipoDePago)
                <div class="col-md-6">
                    <label {!! $tipoDePago->labelHtml !!}>
                        <input type="radio" 
                            name="tipo_pago_codigo" 
                            value="{{ $tipoDePago->codigo }}"
                            {!! $tipoDePago->inputHtml !!}>
                            {{ $tipoDePago->nombre }}
                    </label>
                </div><!-- col -->
                @endforeach
              </div><!-- row -->
            </div><!-- card-body -->
          </div><!-- card -->
        </div><!-- col -->


        <div class="col">
          <div class="card">
            <div class="card-header">Total</div>
            <div class="card-body">
              <table width="100%">
                <tr>
                  <td width="50%">Método de pago:</td>
                  <td><select name="metodo_pago_id" class="form-control">
                      @foreach ($metodosDePago as $metodoDePago)
                        <option value="{{ $metodoDePago->id }}">{{ $metodoDePago->nombre }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Forma de pago:</td>
                  <td><select name="forma_pago_id" class="form-control">
                      @foreach ($formasDePago as $formaDePago)
                        <option value="{{ $formaDePago->id }}">{{ $formaDePago->codigo}} {{ substr($formaDePago->descripcion, 0, 25) }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Uso CFDI:</td>
                  <td><select name="uso_cfdi_id" class="form-control">
                      @foreach ($usosCFDI as $usoCFDI) 
                        <option value="{{ $usoCFDI->id }}">{{ $usoCFDI->codigo}} {{ substr($usoCFDI->descripcion, 0, 25) }}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>
              </table>
            </div><!-- card-body -->
          </div><!-- card -->
        </div><!-- col -->
        
        <div class="col">
          <div class="card">
            <div class="card-header">Acciones</div>
            <div class="card-body">
              <input type="hidden" name="prepago" value="0">
              @if (App\Session::generaPrepagos())
              <div class="form-group">

                <input type="checkbox" name="prepago" value="1" id="prepago">
                <label for="prepago">Generar prepagos</label>
              </div>
              @endif

              <input type="hidden" name="roundtrip" value="0">
              @if (App\Session::generaRoundtrips()) 
                  <div class="form-group">
                      <input type="checkbox" name="roundtrip" value="1" id="roundtrip">
                      <label for="roundtrip">Generar roundtrip</label>
                  </div>
              @endif
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      @php
                        $contador=0;  
                      @endphp 
                       @foreach ($lista as $id=>$row)
                        <!-- Si algún código contiene signo de +, es un prepago y no debe mostrar botón de enviar por correo-->
                        @if(substr($row->servicio->columnaNombre(),0,1)=="+")
                          @php
                            $contador++;  
                          @endphp                          
                        @endif 
                       @endforeach
                     
                      @if($contador>0)
                      @else
                        <input type="input" name="email_user" id="email_user" placeholder="email">
                          <button type="submit" name="action" value="email" class="btn btn-primary"><span class="oi oi-envelope-closed" aria-hidden="true"></span> Enviar</button>
                      @endif 
                     </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" name="action" value="print" class="btn btn-primary"><span class="oi oi-print" aria-hidden="true"></span> Imprimir</button>
                    <!--<button id="263056" class="k-button k-button-icontext" onclick="imprimirBoleto(263056)">Redimir & Imprimir</button>-->
                    <a href="{{ url("/") }}" class="btn btn-secondary"><span class="oi oi-media-skip-backward" title="Regresar" aria-hidden="true"></span> Regresar</a>
                </div>
                </div>
              </div>
              </div>
            </div>
          </div>
  </div><!-- row -->

    <br>
    <div class="row">
      <div class="col offset-8">

      </div>
    </div>


    <div class="row">
        <div class="col">
            @include("sesion/_lista")
           
        </div>
    </div>
    <div class="row">
        <div class="col">
            <span id="notification"></span>
            
        </div>

    </div>
</div>
</form>
@endsection
