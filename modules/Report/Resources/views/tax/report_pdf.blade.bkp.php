<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte impuestos</title>
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
            <p align="center" class="title"><strong>Reporte de impuestos</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td>
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td>
                        <p><strong>Ruc: </strong>{{$company->number}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Establecimiento: </strong>{{$establishment->description}}</p>
                    </td>
                </tr>
            </table>
        </div>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    @php
                        $acum_total=0;
                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th  class="text-left">Fecha emisión</th>
                                <th  class="text-center">Cliente</th>
                                <th  class="text-center">Documento</th>
                                <th  class="text-center">Base</th>

                                <th  class="text-center">Descuento</th>

                                @foreach($taxTitles as $tt)
                                    <th  class="text-right">{{ $tt->name}}({{ $tt->rate}})</th>
                                @endforeach

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->created_at}}</td>
                                    <td class="celda">{{$value->customer->name}}</td>
                                    <td>
                                        <div>{{$value->type_document->name}}</div>
                                        <div>{{$value->prefix}}{{$value->number}}</div>
                                    </td>
                                    <td>$ {{$value->total}}</td>

                                    <td>$ {{$value->total_discount}}</td>
                                    <td>$ {{$value->total_discount}}</td>
                                    <td>$ {{$value->total_discount}}</td>
                                    <td>$ {{$value->total_discount}}</td>
                                    <td>$ {{$value->total_discount}}</td>

                                    <td>$ {{$value->total_discount}}</td>

                                </tr>

                                @php
                                    $acum_total += $value->total;
                                @endphp
                            @endforeach
                            <tr>
                                <td class="celda" colspan="3"></td>
                                <td class="celda" ><strong>Total</strong></td>
                                <td class="celda">{{$acum_total}}</td>
                            </tr>
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
