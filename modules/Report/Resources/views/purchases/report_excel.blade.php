<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Compras</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Compras</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$company->name}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>N° Documento: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>
                    <td>
                        <p><strong>Establecimiento: </strong></p>
                    </td>
                    <td align="center">{{$establishment->address}} - {{$establishment->address}} - {{$establishment->country->name}} - {{$establishment->department->name}} - {{$establishment->city->name}}</td>
                </tr>
            </table>
        </div>
        <br>
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
                                <th>Estado</th>
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
                                <td class="celda">{{$value->state_type->description}}</td>
                                <td class="celda">{{$value->currency_type_id}}</td> 
                                <td class="celda">{{$value->state_type_id == '11' ? 0 : $value->total + $value->total_perception}}</td>

                                
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
                                <td class="celda" colspan="9"></td>
                                <td class="celda" >Total:</td>
                                <td class="celda">{{ number_format($sum_total, 2, ".", "") }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
