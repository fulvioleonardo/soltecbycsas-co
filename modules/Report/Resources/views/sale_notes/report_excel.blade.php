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
            <h3 align="center" class="title"><strong>Reporte Nota de Venta</strong></h3>
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
                                <th class="text-center">Fecha Emisión</th>
                                <th class="">Usuario/Vendedor</th>
                                <th>Cliente</th>
                                <th>Nota de Venta</th>
                                <th>Estado</th>
                                <th class="text-center">Moneda</th>
                                <th class="text-center">Comprobantes</th> 
                                {{-- <th>Cotización</th>
                                <th>Caso</th> --}}
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                <td class="celda">{{$value->user->name}}</td>
                                <td>{{$value->customer->name}}</td>
                                <td>{{$value->number_full}}</td>
                                <td>{{$value->state_type->description}}</td>
                                <td>{{$value->currency_type_id}}</td>
                                <td>
                                    @foreach ($value->documents as $doc)
                                         <label class="d-block">{{$doc->number_full}}</label>
                                    @endforeach
                                </td>
                                
                                {{-- <td class="celda">{{ ($value->quotation) ? $value->quotation->number_full : '' }}</td>
                                <td class="celda">{{ isset($value->quotation->sale_opportunity) ? $value->quotation->sale_opportunity->number_full : '' }}</td> --}}

                                @if($value->state_type_id == '11')
                                    
                                    <td class="celda">0</td>

                                @else
                                    <td class="celda">{{ $value->total}}</td>
                                @endif 
                            </tr>
                            
                            @endforeach
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
