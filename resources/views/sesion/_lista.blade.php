					<table class="table table-bordered table-striped">
						<tr>
							<th>Categoria</th>
							<th>Nombre</th>
							<th>Info</th>
							<th>Cantidad</th>
							@if(env("NEOPOS_VEHICULOS"))
								<th>Descuento</th>
							@endif
							<th>Precio</th>
							<th>Total</th>
						</tr>
					@php
						$total = 0;
					@endphp	
					@foreach ($lista as $id=>$row)
						<tr>
							<td>
								{{ $row->servicio->categoria->nombre }}
							</td>
							<td>{{ $row->servicio->columnaNombre() }}</td>
							<td>{{ $row->servicio->columnaInfo() }}</td>
							<td align="right">{{ $row->cantidad }}</td>
							@if(env("NEOPOS_VEHICULOS"))
							<td align="right"></td>
							@endif
							<!-- Columna de precios unitarios-->
							<td align="right">$
								@if($moneda->id==2)

									@if($row->precio["dll"]==0)
										{{ number_format(($row->precio["dll"]),2) }}
									@else
										{{ number_format(($row->precio["dll"]+$row->precio["ivadll"]+$row->precio["tupdll"]),2) }}
									@endif
							 @else
									 @if($row->precio["mxn"]==0)
										{{ number_format(($row->precio["mxn"]),2) }}
									 @else
										{{ number_format(($row->precio["mxn"]+$row->precio["ivamxn"]+$row->precio["tupmxn"]),2) }}
									 @endif
									
							 @endif
							 {{$moneda->nombre}}
							</td>

							<!-- Columna de precios unitarios * cantidad-->
							<td align="right">
								 @if($moneda->id==2)
								 	@if($row->precio["dll"]==0)
								 		{{ number_format(($row->cantidad*($row->precio["dll"])),2) }}
								 		@php
								 			$total = $total+$row->cantidad*($row->precio["dll"]);
								 		@endphp
								 	@else
								 		{{ number_format(($row->cantidad*($row->precio["dll"]+$row->precio["ivadll"]+$row->precio["tupdll"])),2) }}
								 		@php
								 			$total = $total+$row->cantidad*($row->precio["dll"]+$row->precio["ivadll"]+$row->precio["tupdll"]);
								 		@endphp
								 	@endif
									
								@else
									@if($row->precio["mxn"]==0)
								 		{{ number_format($row->cantidad*($row->precio["mxn"]),2) }}
								 		@php
								 			$total = $total+$row->cantidad*($row->precio["mxn"]);
								 		@endphp
								 	@else
								 		{{ number_format(($row->cantidad*($row->precio["mxn"]+$row->precio["ivamxn"]+$row->precio["tupmxn"])),2) }}
								 		@php
								 			$total = $total+$row->cantidad*($row->precio["mxn"]+$row->precio["ivamxn"]+$row->precio["tupmxn"]);
								 		@endphp
								 	@endif

									
								@endif
								{{$moneda->nombre }}
							</td>
							
						</tr>
					@endforeach
						<tr>
							@if(env("NEOPOS_VEHICULOS"))
								<td align="right" colspan="6"><strong>Total:</strong></td>
							@else
								<td align="right" colspan="5"><strong>Total:</strong></td>
							@endif
							
							<td align="right">$<strong> {{ number_format($total,2) }} {{$moneda->nombre }}</strong></td>
						</tr>
						<tr>
								@if(env("NEOPOS_VEHICULOS"))
									<td align="right" colspan="6">Pago:</td>
								@else
									<td align="right" colspan="5">Pago:</td>
								@endif
								
								<td><input type="text" onkeyup="updateCalc(this)" size="4" class="form-control"> </td>
						</tr>
						<tr>
								@if(env("NEOPOS_VEHICULOS"))
									<td align="right" colspan="6">Cambio:</td>
								@else
									<td align="right" colspan="5">Cambio:</td>
								@endif
								
								<td align="right" id="calculadoraCambio"></td>
						</tr>
					</table>
	 <script>
function updateCalc(input) {
		let label = document.getElementById('calculadoraCambio');
		let total = parseFloat({{ $resumen["total"] }});
		let pago = parseFloat(input.value);
		label.innerHTML =  new Intl.NumberFormat('es-MX', {style:'decimal', maximumFractionDigits: 2}).format(pago-total);
}
	 </script>



