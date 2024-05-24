<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Documento POS</title> 
    </head>
    <body>
        @include('report::document_pos.data_company')

        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="">Usuario/Vendedor</th>
                                <th class="">Tipo Documento</th>
                                <th class="text-center">Documento</th>
                                <th class="text-center">Fecha emisión</th>
                                <th class="">Cliente</th>
                                <th class="">N° Documento</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Moneda</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                                @php
                                    $row = $value->getRowResource();
                                @endphp
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{ $row['user_name'] }}</td>
                                    <td class="celda">{{ $row['document_type_description'] }}</td>
                                    <td class="celda">{{ $row['number_full'] }}</td>
                                    <td class="celda">{{ $row['date_of_issue'] }}</td>
                                    <td class="celda">{{ $row['customer_name'] }}</td>
                                    <td class="celda">{{ $row['customer_number'] }}</td>
                                    <td class="celda">{{ $row['state_type_description'] }}</td>
                                    <td class="celda">{{ $row['currency_type_id'] }}</td>
                                    <td class="celda">{{ $row['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
