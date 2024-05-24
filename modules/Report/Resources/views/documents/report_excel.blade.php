<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Documentos</strong></h3>
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
                                <th class="">Usuario/Vendedor</th>
                                <th>Tipo Doc</th>
                                <th>Número</th>
                                <th>Fecha emisión</th>
                                <th>Doc. Afectado</th>
                                <th>Cotización</th>
                                <th>Caso</th>
                                <th>Cliente</th>
                                <th>N° Documento</th>
                                <th>Estado</th>
                                <th class="">Moneda</th> 
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @php
                                $sum_total = 0;
                            @endphp

                            @foreach($records as $key => $value)
                            @php
                                $serie_affec = '';
                            @endphp
                            <tr>
                                <td class="celda">{{$loop->iteration}}</td>
                                <td class="celda">{{$value->user->name}}</td>
                                <td class="celda">{{$value->type_document->name}}</td>
                                <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                  
                                @php 
                                    if(in_array($value->type_document_id,[2,3]) && $value->reference){

                                        $series = $value->reference->series; 
                                        $number =  $value->reference->number;
                                        $serie_affec = $series.' - '.$number;
                                    }

                                    $sum_total += $value->total;

                                @endphp
                                <td class="celda">{{$serie_affec }} </td>

                                <td class="celda">{{ ($value->quotation) ? $value->quotation->number_full : '' }}</td>
                                <td class="celda">{{ isset($value->quotation->sale_opportunity) ? $value->quotation->sale_opportunity->number_full : '' }}</td>

                                <td class="celda">{{$value->customer->name}}</td>
                                <td class="celda">{{$value->customer->number}}</td>
                                <td class="celda">{{$value->state_document->name}}</td>
                               
                                <td class="celda">{{$value->currency_type_id}}</td>
                                
                                <td class="celda">{{$value->total}}</td>

                                
                            </tr>
                            @endforeach
                            
                            <tr>
                                <td class="celda" colspan="11"></td>
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
