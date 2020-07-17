<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>Prueba</title>
    
</head>
<body>
@php
	$tiene_cliente=0;
@endphp
@foreach ($boletos as $boleto)
	@if($boleto->cliente_id>0)
		@php
			$tiene_cliente++;
		@endphp
	@endif
@endforeach
<table style="font-family: Gotham,'Helvetica neue', Helvetica, Arial, Sans-serif; font-size: 13px; color: #727272; text-align: center;" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    @if($tiene_cliente>0)
    	<td colspan="3">
    @else
    	<td colspan="2">
    @endif
    	<img style="width: 100%; min-width: 700px; max-width: 830px;" src="http://winjet.mx/images/head-email.jpg">
    </td>
  </tr>
  <tr>
    @if($tiene_cliente>0)
    	<td colspan="3">
    @else
    	<td colspan="2">
    @endif
      <h1 style="font-size: 28px; color: #185a78;">BOLETO DIGITAL</h1>
    </td>
  </tr>
  <tr>
	@if($tiene_cliente>0)
    	<td colspan="3">
    @else
    	<td colspan="2">
    @endif
      <h3>Detalles de la transacción:</h3>
    </td>
  </tr>
  <tr>
	<td><h3 style="font-size: 14px; color: #185a78;">Categoría</h3></td>
	@if($tiene_cliente>0)
		<td><h3 style="font-size: 14px; color: #185a78;">Nombre</h3></td>	
	@endif
	<td><h3 style="font-size: 14px; color: #185a78;">Código</h3></td>
  </tr>

  
	@foreach ($boletos as $boleto)
	<tr>
		<td>
			{{$boleto->categoria->nombre}}
		</td>
		@if($tiene_cliente>0)
			<td>{{$boleto->nombre}}</td>
		@endif
			<td>
			<img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($boleto->codigo, 'C39+', 1, 50) }}">
			{{$boleto->codigo}}
		</td>
	</tr>
	<tr>
		@if($tiene_cliente>0)
			<td colspan="3"><br></td>
		@else
			<td colspan="2"><br></td>
		@endif
		
	</tr>
	@endforeach
</table>
            
</body>
</html>
