<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Inventario</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Constancia {{ $type }}</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Producto: </b></p>
                    </td>
                    <td>
                        <p>{{$product}}</p>
                    </td>
                    <td>
                        <p><strong>Cantidad: </strong></p>
                    </td>
                    <td>
                        <p>{{ $quantity }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Almacen destino: </strong></p>
                    </td>
                    <td>{{ $warehouse }}</td>

                </tr>
                <tr>
                    <td>
                        <p><strong>Motivo: </strong></p>
                    </td>
                    <td>{{ $reason }}</td>
                </tr>
            </table>
        </div>
        <br>
    </body>
</html>
