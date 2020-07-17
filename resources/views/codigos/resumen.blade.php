     
Codigos generados:<br>

@foreach ($boletos as $boleto)
	{{ $boleto->codigo }}:  {{$boleto->categoria->nombre}}
	
	@if($moneda->id==2)
  			${{ $boleto->categoria->precio_cobrado_dolares}}
  	@else
  			${{ $boleto->precio }}
	@endif

  	{{$moneda->nombre }}
<br>
@endforeach



