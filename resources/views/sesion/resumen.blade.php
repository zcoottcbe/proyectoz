        <table class="table">
          <tr>
            <td align="right"><strong>Servicios:</strong></td>
            <td align="right">{{ $resumen['cantidad'] }}</td>
          </tr>
          <tr>
            <td align="right"><strong>Total:</strong></td>
            <td align="right">${{ number_format($resumen['precio'],2) }} {{$moneda->nombre }}</td>
          </tr>
          <!--<tr>
            <td colspan="2" align="right">
            <a href="{{ route('sesion.moneda', $moneda_alterna->id) }}">Cambiar a {{ $moneda_alterna->nombre}}</a>
            </td>
          </tr>-->
        </table>
