<table style="font-family: Gotham,'Helvetica neue', Helvetica, Arial, Sans-serif; font-size: 13px; color: #727272; text-align: center;" width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">
      <img style="width: 100%; min-width: 700px; max-width: 830px;" src="http://api.transcaribe.net/email_header.jpg">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <h1 style="font-size: 28px; color: #185a78;">GRACIAS POR SU COMPRA / THANKS FOR YOUR PURCHASE.</h1>
      <h2 style="color: rgb(221,38,38); font-size: 15px; font-weight: bold;">SU RESUMEN / YOUR SUMMARY</h2>
      <p>
        <strong>NO. DE FOLIO / FOLIO NUMBER:</strong> {{ $result["ticketfolio"] }}<br/>
        <strong>RAZÓN SOCIAL / BUSINESS NAME:</strong> TRANSBORDADORES DEL CARIBE, S.A. DE C.V.<br/>
        <strong>RFC:</strong> TCA030312II3<br/><br/>
        <strong>DIRECCIÓN FISCAL / BUSINESS ADDRESS</strong><br/>
        <strong>CALLE Y NÚMERO / STREET AND NUMBER:</strong> CALLE 6 NORTE, #14 ENTRE 10 Y 15<br/>
        <strong>COLONIA / NEIGHBORHOOD:</strong> CENTRO<br/>
        <strong>CIUDAD / CITY:</strong> COZUMEL<br/>
        <strong>ESTADO / STATE:</strong> QUINTANA ROO<br/>
        <strong>CÓDIGO POSTAL / ZIP:</strong> 77600<br/>
      </p>
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <br>
    <!--  @if (isset($datos["fecha"]))
        <strong>FECHA DE CRUCE / DEPARTURE DATE:</strong> {{ strtoupper($datos["fecha"]) }}<br/>
      @endif
      @if (isset($datos["horario"]))
        <strong>HORA DE CRUCE / DEPARTURE TIME:</strong> {{ strtoupper($datos["horario"]) }}<br/>
      @endif
      @if (isset($datos["placa"]))
        <strong>NUMERO DE MATRICULA / REGISTRATION CART:</strong> {{ strtoupper($datos["placa"]) }}<br/>
      @endif
      @if (isset($datos["operador"]))
        <strong>OPERADOR / CART DRIVER:</strong> {{ strtoupper($datos["operador"]) }}<br/>
      @endif
      @if (isset($datos["puerto"]))
        <strong>PUERTO DE EMBARQUE / ABOARD PORT::</strong> {{ strtoupper($datos["puerto"]) }}<br/>
      @endif
      @if (isset($origen))
        <strong>TIPO DE OPERACIÓN / PAYMENT TYPE:</strong> {{ strtoupper($origen) }}<br/>
      @endif -->

@if ($datos_operador)
      <b>FECHA DE CRUCE / DEPARTURE DATE:</b> {{ $datos_fecha }}<br>
      <b>HORA DE CRUCE / DEPARTURE TIME:</b> {{ $datos_horario }}<br>
      <b>NUMERO DE MATRICULA / REGISTRATION CART:</b> {{ $datos_placa }}<br>
      <b>OPERADOR / CART DRIVER:</b> {{ $datos_operador }}<br>
      <b>PUERTO DE EMBARQUE / ABOARD PORT:</b> {{ $datos_puerto }}<br>
@endif
    <br/>
    </td>
  </tr>
	<tr>
          <td>
            <p>
            </p>
          </td>
        </tr>
  <tr>
    <td width="50%" style="vertical-align: top;">
      <ul style="list-style: disc; padding: 10px 20px; text-align: left;">
        <li>LLEGAR 90 MINUTOS ANTES DE LA HORA DE CRUCE EN LA QUE QUIERE REALIZAR SU VIAJE</li>
        <li>FAVOR DE IMPRIMIR ESTA HOJA Y PRESENTARLA EN LA TAQUILLA.</li>
      	<li><h3>NUESTROS CRUCES CIERRAN EL REGISTRO 45 MINUTOS ANTES DE LA HORA DE SALIDA DEL CRUCE</h3></li>
      </ul>
    </td>
    <td width="50%" style="vertical-align: top;">
      <ul style="list-style: disc; padding: 10px 20px; text-align: left;">
        <li>PLEASE ARRIVE 90 MINUTES BEFORE THE DEPARTURE TIME IN WHICH YOU WANT TO MAKE YOUR TRIP.</li>
        <li>PLEASE PRINT THIS SHEET AND PRESENT IT AT THE TICKET OFFICE.</li>
	<li><h3>OUR CROSSES CLOSE THE REGISTRATION 45 MINUTES BEFORE THE DEPARTURE TIME OF THE CROSSING</h3></li>
      </ul>
    </td>
  </tr>
  @if (!$pdf)
    <tr>
      <td colspan="2">
        <h2 style="font-size: 14px; font-weight: bold;">¿REQUIERE FACTURA? / DO YOU REQUIRE AN INVOICE?<br/>
        LEA LAS SIGUIENTES INSTRUCCIONES / READ THE FOLLOWING INSTRUCTIONS</h2>
      </td>
    </tr>
    <tr>
      <td width="50%" style="vertical-align: top;">
        <p style="text-align: justify; padding: 0 20px;">EN TRANSCARIBE ESTAMOS SIEMPRE PREOCUPADOS POR BRINDAR LA MEJOR ATENCIÓN Y SERVICIO A NUESTROS CLIENTES, ES POR ELLO QUE A PARTIR DE AHORA PODRÁ GENERAR SU FACTURA DESDE ESTE MISMO SITIO, SIN TENER QUE ESPERAR POR ELLA EN NUESTRAS INSTALACIONES.</p>
        <p>SOLAMENTE DEBERÁ SEGUIR 5 SENCILLOS PASOS:</p>
        <ul style="list-style: disc; padding: 10px 20px; text-align: left;">
          <li>COPIE EL TICKETFOLIO DE LA PARTE INFERIOR</li>
          <li>DE CLICK EN EL BOTÓN "GENERAR FACTURA"</li>
          <li>INGRESE SU RFC, TICKETFOLIO Y MONTO</li>
          <li>INGRESE O MODIFIQUE SI ES NECESARIO SUS DATOS FISCALES</li>
          <li>ENVÍE LA FACTURA VÍA CORREO ELECTRÓNICO O DESCÁRGUELA EN PDF</li>
        </ul>
        <p>NOTA:</p>
        <ul style="list-style: disc; padding: 0 20px 10px; text-align: left;">
          <li>PODRÁ OBTENER SU FACTURA AL INSTANTE</li>
          <li>SE GENERA UNA SOLA FACTURA POR TICKET</li>
          <li>LA FECHA LIMITE PARA REALIZAR SU FACTURA ES EL DÍA 3 DEL MES SIGUIENTE A SU COMPRA</li>
          <li>EN CASO DE TENER ALGUNA DUDA AL MOMENTO DE GENERAR SU FACTURA PUEDE CONSULTAR NUESTROS VIDEOS <a href="https://www.youtube.com/watch?v=1PB-DQQ4ffs" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.youtube.com/watch?v%3D1PB-DQQ4ffs&amp;source=gmail&amp;ust=1475762203773000&amp;usg=AFQjCNEjkLOGhkONVD99DjDCXmQItQSyNQ">AQUI</a></li>
        </ul>
      </td>
      <td width="50%" style="vertical-align: top;">
        <p style="text-align: justify; padding: 0 20px;">TRANSCARIBE TEAM IS ALWAYS CONCERNED ABOUT PROVIDING THE BEST SERVICE TO OUR CUSTOMERS, THAT IS WHY YOU CAN GENERATE YOUR INVOICE FROM THIS SAME SITE, WITHOUT HAVING TO ATTEND IN OUR FACILITIES.</p>
        <p>YOU ONLY NEED TO FOLLOW NEXT 5 SIMPLE STEPS:</p>
        <ul style="list-style: disc; padding: 10px 20px; text-align: left;">
          <li>COPY THE TICKETFOLIO FROM THE BOTTOM</li>
          <li>CLICK ON THE "GENERATE INVOICE" BUTTON</li>
          <li>ENTER YOUR RFC, TICKETFOLIO AND AMOUNT</li>
          <li>ENTER OR MODIFY IF NECESSARY YOUR TAX DATA</li>
          <li>SEND THE INVOICE VIA EMAIL OR DOWNLOAD IT IN PDF</li>
        </ul>
        <p>NOTE:</p>
        <ul style="list-style: disc; padding: 0 20px 10px; text-align: left;">
          <li>YOU CAN GET YOUR INVOICE INSTANTLY</li>
          <li>A SINGLE INVOICE IS GENERATED PER TICKET</li>
          <li>THE DEADLINE TO MAKE YOUR INVOICE IS THE 3RD DAY OF THE MONTH FOLLOWING YOUR PURCHASE</li>
          <li>IN CASE YOU HAVE ANY DOUBTS WHEN GENERATING YOUR INVOICE YOU CAN CONSULT OUR VIDEOS <a href="https://www.youtube.com/watch?v=1PB-DQQ4ffs" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?hl=en&amp;q=https://www.youtube.com/watch?v%3D1PB-DQQ4ffs&amp;source=gmail&amp;ust=1475762203773000&amp;usg=AFQjCNEjkLOGhkONVD99DjDCXmQItQSyNQ">HERE</a></li>
        </ul>
      </td>
     </tr>
   @endif
</table>

@foreach ($result["categorias"] as $cat)
  <table style="font-family: Gotham,'Helvetica neue', Helvetica, Arial, Sans-serif; font-size: 13px; color: #727272; text-align: center;" width="100%" border="0" cellpadding="0" cellspacing="0">
    {{-- */ $col = 0;  /* --}}
    @if (array_key_exists($cat["id"], $boletos))
      @foreach ($boletos[$cat["id"]] as $boleto)
        <tr>
          <td>
            <p>
              <strong>TIPO DE VEHICULO / VEHICLE TYPE:</strong><br/>
              {{ strtoupper($cat["nombre"]) }}<br/>
              @if ($pdf)
                <img src="data:image/png;base64,{{ \DNS1D::getBarcodePNG($boleto['folioWeb'], 'C39+', 1, 50) }}"><br/>
              @else
                <img src="{{ $message->embedData(base64_decode(\DNS1D::getBarcodePNG($boleto['folioWeb'], 'C39', 1, 50)), $boleto['folioWeb'] . 'BARCODE.png') }}"><br/>
              @endif
              <br/>
              <strong>{{ $boleto['folioWeb'] }}</strong>
            </p>
          </td>
        </tr>
        {{-- */ $col++; /* --}}
      @endforeach
    @endif
  </table>
@endforeach
<br/>
<table style="font-family: Gotham,'Helvetica neue', Helvetica, Arial, Sans-serif; font-size: 11px; color: #727272; text-align: center;" width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr style="color: #5a5959; background: #c8c8c8; font-weight: bold;">
    <td style="padding: 10px 15px; width: 25%;">TIPO DE VEHÍCULO / VEHICLE TYPE</td>
    <td style="padding: 10px 15px; width: 25%;">NO. DE PREPAGOS / NO. OF PREPAYMENTS</td>
    <td style="padding: 10px 15px; width: 25%; text-align: right;">PRECIO POR PREPAGO / PREPAYMENT PRICE</td>
    <td style="padding: 10px 15px; width: 25%; text-align: right;">TOTAL</td>
  </tr>
  @foreach ($result["categorias"] as $cat)
    <tr style="background: #e7e6e6;">
      <td style="padding: 10px 15px;">{{ strtoupper($cat["nombre"]) }}</td>
      <td style="padding: 10px 15px;">{{ $cat["cantidad"] }}</td>
      <td style="padding: 10px 15px; text-align: right;">${{ number_format($cat["precio"],2) }} MXN</td>
      <td style="padding: 10px 15px; text-align: right;">${{ number_format($cat["precio"]*$cat["cantidad"],2) }} MXN</td>
    </tr>
  @endforeach
  <tr style="color: #5a5959; background: #e7e6e6;">
    <td colspan="2">&nbsp;</td>
    <td style="padding: 10px 15px; font-weight: bold; text-align: right;">SUBTOTAL</td>
    <td style="padding: 10px 15px; text-align: right;">${{ number_format($result["subtotal"],2) }} MXN</td>
  </tr>
  <tr style="color: #5a5959; background: #e7e6e6;">
    <td colspan="2">&nbsp;</td>
    <td style="padding: 10px 15px; font-weight: bold; text-align: right;">IVA</td>
    <td style="padding: 10px 15px; text-align: right;">${{ number_format($result["iva"],2) }} MXN</td>
  </tr>
  <tr style="color: #5a5959; background: #e7e6e6;">
    <td colspan="2">&nbsp;</td>
    <td style="padding: 10px 15px; font-weight: bold; text-align: right;">TOTAL</td>
    <td style="padding: 10px 15px; text-align: right;">${{ number_format($result["total"],2 ) }} MXN</td>
  </tr>
</table>
