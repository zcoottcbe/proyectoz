echo '
             <script type="text/javascript">
              $( document ).ready(function() {
                  var popupNotification = $(\'#popupNotification\').kendoNotification({
                    autoHideAfter: 5000,
                    button: false,
                    hideOnClick: false,
                    position: {
                      pinned: true,
                      top: 20,
                      left: null,
                      bottom: null,
                      right: 20
                    }
                  }).data(\'kendoNotification\');
             

                  popupNotification.hide().show(\' Prueba...\', \'info\');

              
                 
                startConnection = function() {
                  if (!qz.websocket.isActive()) {
                    popupNotification.hide().show(\' Conectando con QZ Tray...\', \'info\');
                    qz.websocket.connect().then(function() {
                      popupNotification.hide().show(\' Conexión establecida con QZ Tray\', \'success\');
                      
                      imprimirBoletoactual('.$boleto->boleto_id.');  
                      
                    }).catch(handleConnectionError);
                  } else {
                    popupNotification.hide().show(\' Conexión activa con QZ Tray\', \'info\');
                  }
                }

                handleConnectionError = function() {
                  popupNotification.hide().show(\'Error de conección con QZ Tray\', \'error\');

                  if (err.target != undefined) {
                    if (err.target.readyState >= 2) {
                      popupNotification.hide().show(\' La conección con QZ Tray fue cerrada\', \'error\');
                    } else {
                      popupNotification.hide().show(\' Ocurrio un error de conección con QZ Tray: \' + err, \'error\');
                    }
                  } else {
                    popupNotification.hide().show(err, \'error\');
                  }   
                }

                
                imprimirBoletoactual = function(boletoId) {
                if (!qz.websocket.isActive()) {
                  popupNotification.hide().show(\' No existe una conexión con QZ Tray o fue cerrada\', \'error\');
                }
                else {
                  var config = qz.configs.create(\'impresora_boletos\');
                  var data = [\'prueba\'];
                  return qz.print(config, data);
                 

                  
                }
            }


              startConnection();

              });

            </script>
      ';