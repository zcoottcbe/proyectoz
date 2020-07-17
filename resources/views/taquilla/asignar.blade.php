@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Asignar la taquilla actual</div>

                <div class="card-body">
                  <div class="container">
                    <div class="row">
                    @foreach ($taquillas as $taquilla)
                      <div class="col-md-4">
                        <a href="{{ route("sesion.taquilla", [$taquilla->id]) }}" class="btn btn-block btn-primary" style="margin-bottom: 5px">{{ $taquilla->nombre }}</a>
                      </div>
                    @endforeach
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

