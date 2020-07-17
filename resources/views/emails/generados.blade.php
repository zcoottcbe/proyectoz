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
	<td><h3 style="font-size: 14px; color: #185a78;">Tipo de Vehículo</h3></td>
	<td><h3 style="font-size: 14px; color: #185a78;">Placas</h3></td>
  </tr>
	@foreach ($boletos as $boleto)
	<tr>
		<td>
			{{$boleto->categoria->nombre}}
		</td>

		<td>
			{{$boleto->placas}}
		</td>	
	</tr>
	<tr>
		<td colspan="2"><br></td>
	</tr>
	<tr>
		<td>
			Código:
		</td>
		<td>
			<img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($boleto->codigo, 'C39+', 1, 50) }}">
		</td>
	</tr>
	<tr>
		<td colspan="2"><br></td>
	</tr>
	@endforeach
</table>
            
</body>
</html>
