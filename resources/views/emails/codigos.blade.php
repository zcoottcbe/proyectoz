Esto todavía es una prueba.......	



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

<table style="font-family: Gotham,'Helvetica neue', Helvetica, Arial, Sans-serif; font-size: 13px; color: #727272; text-align: center;" width="100%" b
order="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">
      <img style="width: 100%; min-width: 700px; max-width: 830px;" src="http://api.transcaribe.net/email_header.jpg">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <h1 style="font-size: 28px; color: #185a78;">BOLETO DIGITAL</h1>
    </td>
  </tr>
  <tr>
	<td colspan="2">
      <h3>Detalles de la transacción:</h3>
    </td>
  </tr>

  <tr>
	<td>Tipo de Vehículo</td>
	<td>Placas</td>
	<td>Folio</td>
  </tr>
	@foreach ($boletos as $boleto)
	<tr>
		<td>
			{{$boleto->categoria->nombre}}
		</td>

		<td>
			{{$boleto->placas}}
		</td>	

		<td>
			<img src="{{ $message->embedData(base64_decode(\DNS1D::getBarcodePNG($boleto->codigo, 'C39', 1, 50)), $boleto->nombre.'-'.$boleto->placas.'.png') }}"> 
			{{--<b>Aquí a a ir el código de barras, pero todavía no queda :(</b>
	<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('11', 'C39')}}"/>


			<img src="data:image/jpg;base64,{{DNS1D::getBarcodeHTML($boleto->codigo, 'C39')}}" alt="{{$boleto->codigo}}"></td>

			 '<img src="data:image/png;base64,'.DNS1D::getBarcodePNG("4", "C39+").'"/>' --}}

			
			{{-- <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($boleto->codigo, 'C39', 1, 50) }}"> 
			<img src="data:image/png;base64,{{DNS1D::getBarcodeJPG('Hello World', 'C39')}}" alt="barcode for hello world"> 
			<img src="data:image/png;base64,{{DNS1D::getBarcodeHTML('Hello World', 'C39')}}" alt="barcode for hello world">--}}


			




		</td>
		{{-- {{ $boleto->codigo }}: {{$boleto->categoria->nombre}} (${{ $boleto->precio }})--}}
	</tr>
	
	{{--<img src="{{ $message->embedData(base64_decode(\DNS1D::getBarcodePNG($boleto->codigo, 'C39', 1, 50)), $boleto->nombre.'.png') }}">   Esto adjunta la imagen--}}
	@endforeach
</table>









             
</body>
</html>
