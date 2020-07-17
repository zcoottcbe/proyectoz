@extends('layouts.admin')

@section('content')
<span id="popupNotification"></span>

<h2>Redimir boletos</h2>

<br/>
<p>Se seleccionaron los siguientes boletos:</p>

<table id="boletos-grid">
	<thead>
		<tr>
			<th>Boleto Id</th>
			<th><span class="d-none">Acción</span></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($boletos as $boleto)
			<tr>
				<td>{{ $boleto->boleto_id }}</td>
				<td>
					<button id="{{ $boleto->boleto_id }}" class="k-button k-button-icontext" onclick="imprimirBoleto({{ $boleto->boleto_id }})">Redimir & Imprimir</button>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
<br/>

{{ link_to_route('redimir-boletos', 'Regresar', null, array('class'=>'k-button k-button-icontext'))}}

<br/><br/>
<p>Para poder imprimir boletos verifique tener instalado y abierto el programa QZ Tray y que la impresora este configurada con el nombre: "impresora_boletos"</p>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
	var popupNotification = $('#popupNotification').kendoNotification({
		autoHideAfter: 10000,
		button: false,
		hideOnClick: false,
		position: {
			pinned: true,
			top: 20,
			left: null,
			bottom: null,
			right: 20
		}
	}).data('kendoNotification');

	$('#boletos-grid').kendoGrid();

	startConnection = function() {
		if (!qz.websocket.isActive()) {
			popupNotification.hide().show(' Conectando con QZ Tray...', 'info');
			qz.websocket.connect().then(function() {
				popupNotification.hide().show(' Coneccción establecida con QZ Tray', 'success');
			}).catch(handleConnectionError);
		} else {
			popupNotification.hide().show(' Conección activa con QZ Tray', 'info');
		}
	}

	handleConnectionError = function() {
		popupNotification.hide().show('Error de conección con QZ Tray', 'error');

		if (err.target != undefined) {
			if (err.target.readyState >= 2) {
				popupNotification.hide().show(' La conección con QZ Tray fue cerrada', 'error');
			} else {
				popupNotification.hide().show(' Ocurrio un error de conección con QZ Tray: ' + err, 'error');
			}
		} else {
			popupNotification.hide().show(err, 'error');
		}		
	}

	imprimirBoleto = function(boletoId) {
		alert('Imprime');
		/*if (!qz.websocket.isActive()) {
			popupNotification.hide().show(' No existe una conección con QZ Tray o fue cerrada', 'error');
		}
		else {
			$.ajax({
				url: '/admin/redimir-boletos-redimir',
				type: 'GET',
				data: {
					boleto_id: boletoId
				},
				success: function(result) {
					if(result.result == 'success') {

						var config = qz.configs.create('impresora_boletos');

						$.each(result.detail, function(index, value) {
							result.detail[index] = result.detail[index].replace('\\n','\n');
						});

						qz.print(config, result.detail).then(function() {
							popupNotification.hide().show(' Se mando imprimir el boleto #' + boletoId, 'info');
							$('#' + boletoId).hide();
						});

					}
					else {
						popupNotification.hide().show(' No se pudo imprimir el boleto #' + boletoId + ': ' + result.detail, 'error');
					}
					$('#' + boletoId).hide();
				}});
		}*/
	}

	startConnection();
});
</script>
@endsection
