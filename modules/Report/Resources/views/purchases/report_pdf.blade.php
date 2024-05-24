<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Compras</title>
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
            <p align="center" class="title"><strong>Reporte Compras</strong></p>
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
                    <td>
                        <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->country->name}} - {{$establishment->department->name}} - {{$establishment->city->name}}</p>
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
                                <th>Tipo Doc</th>
                                <th>Número</th>
                                <th>F. Emisión</th>
                                <th class="">F. Vencimiento</th>
                                <th>Cliente</th>
                                <th>N° Documento</th>
                                <th class="">F. Pago</th> 
                                <th>Moneda</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                $sum_total = 0;
                            @endphp
        
                            @foreach($records as $key => $value)
                                <tr>

                                
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->document_type->id}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{$value->date_of_due->format('Y-m-d')}}</td>
                                    <td class="celda">{{$value->supplier->name}}</td>
                                    <td class="celda">{{$value->supplier->number}}</td>
                                    <td class="celda">{{isset($value->purchase_payments['payment_method_type']['description'])?$value->purchase_payments['payment_method_type']['description']:'-'}}</td>

                                    <td class="celda">{{$value->currency_type_id}}</td> 
                                    <td class="celda">{{ $value->state_type_id == '11' ? 0 : $value->total}}</td>

                                    @php
                                        $value->total_taxed = (in_array($value->document_type_id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_taxed;
                                        $value->total_igv = (in_array($value->document_type_id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total_igv;
                                        $value->total = (in_array($value->document_type_id,['01','03']) && in_array($value->state_type_id,['09','11'])) ? 0 : $value->total;
                                        $state = $value->state_type_id;

                                        $sum_total += $value->total;
                                    @endphp
                                </tr>
                            @endforeach 
                            
                            <tr>
                                <td class="celda" colspan="8"></td>
                                <td class="celda" >Total:</td>
                                <td class="celda">{{ number_format($sum_total, 2, ".", "") }}</td>
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
