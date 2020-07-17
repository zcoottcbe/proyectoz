<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <title>Prueba</title>
    <script type="text/javascript" src="{{ asset('/qz/dependencies/rsvp-3.1.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/qz/dependencies/sha-256.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/qz/qz-tray.js') }}"></script>
    <script src="{{ asset('/kendo/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/kendo/js/kendo.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/kendo/js/kendo.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/kendo/js/messages/kendo.messages.es-MX.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/kendo/styles/kendo.common.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/kendo/styles/kendo.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/kendo/styles/kendo.default.mobile.min.css') }}" />
</head>
<body>
  <p>
    Aquí va el popup:
    <span id="popupNotification"></span>
  <p>
    Fin del popup.
  <p>
  
  
</body>
</html> 

<script>

function onShow(e) {
    var elementBeingShown = e.element;
}

$("#notification").kendoNotification({
    autoHideAfter: 5000,
    button: false,
    hideOnClick: false,
    position: {
      pinned: true,
      top: 20,
      left: null,
      bottom: null,
      right: 20
    },
    show: onShow

});

qz.websocket.connect().then(function() {
  $("#notification").getKendoNotification().show(".......... DE QZ");
});

 // $("#notification").getKendoNotification().show("ANTES DE QZ");



 startConnection = function() {
    if (!qz.websocket.isActive()) {
      //popupNotification.hide().show(' Conectando con QZ Tray...', 'info');
       $("#notification").getKendoNotification().show("Conectando con QZ... P");
      
      qz.websocket.connect().then(function() { 
        //popupNotification.hide().show(' Coneccción establecida con QZ Tray', 'success');
        $("#notification").getKendoNotification().show("Conexion establecida con QZ");
      }).catch(handleConnectionError);
    }else {
      //popupNotification.hide().show(' Conección activa con QZ Tray', 'info');
      $("#notification").getKendoNotification().show("Conexion activa con QZ");
    }
  }
  $("#notification").getKendoNotification().show("DESPUES DE QZ");

  imprimirBoleto = function(boletoId) {
    alert("imprime");
    if (!qz.websocket.isActive()) {
       $("#notification").getKendoNotification().show("No existe una conección con QZ Tray o fue cerrada");
       //popupNotification.hide().show(' No existe una conección con QZ Tray o fue cerrada', 'error');
    }
    else {
      //alert (boletoId);
      $.ajax({
        url: '/sesion/redimirboletos',
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
              //popupNotification.hide().show(' Se mando imprimir el boleto #' + boletoId, 'info');
              $("#notification").getKendoNotification().show("Se mando imprimir el boleto #"+boletoId);
              //$('#' + boletoId).hide();
            });

          }
          else {
            $("#notification").getKendoNotification().show("No se pudo imprimir el boleto ");
            //popupNotification.hide().show(' No se pudo imprimir el boleto #' + boletoId + ': ' + result.detail, 'error');
          }
          //$('#' + boletoId).hide();
        }});
    }
  }

  startConnection();

</script>

<!--




<script>
$(document).ready(function(){
  //alert ("entra");
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

 
  popupNotification.hide().show(' Esto es una prueba');


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
    if (!qz.websocket.isActive()) {
      popupNotification.hide().show(' No existe una conección con QZ Tray o fue cerrada', 'error');
    }
    else {
      //alert (boletoId);
      $.ajax({
        url: '/sesion/redimirboletos',
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
              //$('#' + boletoId).hide();
            });

          }
          else {
            popupNotification.hide().show(' No se pudo imprimir el boleto #' + boletoId + ': ' + result.detail, 'error');
          }
          //$('#' + boletoId).hide();
        }});
    }
  }

  startConnection();
});
</script>

