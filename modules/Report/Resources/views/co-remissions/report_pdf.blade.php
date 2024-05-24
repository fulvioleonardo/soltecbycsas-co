<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Remisiones</title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 12px;
            }
            
            table {
                width: 100%;
                border-spacing: 0;
                border: 1px solid black;
            }
            
            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }
            
            th {
                padding: 5px;
                text-align: center;
                border-color: #0088cc;
                border: 0.1px solid black;
            }
            
            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 20px !important;
                text-decoration: underline;
            }
            
            p>strong {
                margin-left: 5px;
                font-size: 13px;
            }
            
            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Reporte Remisiones</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td>
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong>{{date('Y-m-d')}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>N° Documento: </strong>{{$company->number}}</p>
                    </td>
                
                </tr>
            </table>
        </div>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha Emisión</th>
                                <th class="">Usuario/Vendedor</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th>Remisión</th>
                                <th>Cotización</th>
                                <th class="text-center">Moneda</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                                @php
                                    $row = $value->getRowResource();
                                @endphp
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$row['date_of_issue']}}</td>
                                    <td class="celda">{{$row['user_name']}}</td>
                                    <td class="celda">{{$row['customer_name']}}</td>
                                    <td class="celda">{{$row['state_type_description']}}</td>
                                    <td class="celda">{{$row['number_full']}}</td>
                                    <td class="celda">{{$row['quotation_number_full']}}</td>
                                    <td class="celda">{{$row['currency_name']}}</td>
                                    <td class="celda">{{$row['total']}}</td>
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
