Codigos de prepago generados:<br>
@foreach ($boletos as $boleto)
  {{ $boleto->codigo }}: {{$boleto->categoria->nombre}} (${{ $boleto->precio }})<br>
@endforeach
