<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Artículos Vendidos</title>
        
        @include('report::commons.styles')
    </head>
    <body>
        @include('report::commons.header')
        
        <div>
            <p align="left" class="title"><strong>Artículos Vendidos</strong></p>
        </div>

        @include('report::co-items-sold.partials.filters')

        @if($records->count() > 0)
            <div class="">
                <div class="">
                    <table class="">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Código</th>
                                <th>Artículo</th>
                                <th>Cantidad</th>
                                <th>Costo</th>
                                <th>Neto</th>
                                <th>Utilidad</th>
                                <th>Impuesto</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $value)
                                @php
                                    $row = $value->getDataReportSoldItems();
                                @endphp
                                <tr>
                                    <td class="celda">{{ $row['type_name'] }}</td>
                                    <td class="celda">{{ $row['internal_id'] }}</td>
                                    <td class="celda">{{ $row['name'] }}</td>
                                    <td class="celda">{{ $row['quantity'] }}</td>
                                    <td class="celda">{{ $row['cost'] }}</td>
                                    <td class="celda">{{ $row['net_value'] }}</td>
                                    <td class="celda">{{ $row['utility'] }}</td>
                                    <td class="celda">{{ $row['total_tax'] }}</td>
                                    <td class="celda">{{ $row['total'] }}</td>
                                </tr>
                            @endforEach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p><strong>No se encontraron registros.</strong></p>
            </div>
        @endif
    </body>
</html>
