<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>REPORTE PRODUCTOS</title>
    </head>
    <body> 
        @if(!empty($records))
            <div class="">
                <div class=" "> 
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">FECHA DE EMISIÓN</th>
                                <th class="">TIPO DOCUMENTO</th>
                                <th class="">SERIE</th> 
                                <th class="">NÚMERO</th> 
                                <th class="">CLIENTE/PROVEEDOR</th> 
                                <th class="">MONEDA</th> 
                                <th class="">UNIDAD DE MEDIDA</th> 
                                <th class="">CÓDIGO INTERNO</th> 
                                <th class="">NOMBRE</th> 
                                <th class="">CANTIDAD</th> 
                                <th class="">PRECIO UNITARIO</th> 
                                <th class="">TOTAL</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @if($type == 'sale')
                                @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$value->document->date_of_issue->format('Y-m-d')}}</td> 
                                    <td class="celda">{{$value->document->type_document->name}}</td>
                                    <td class="celda">{{$value->document->series}}</td>
                                    <td class="celda">{{$value->document->number}}</td>
                                    <td class="celda">{{$value->document->customer->number}} - {{$value->document->customer->name}}</td>
                                    <td class="celda">{{$value->document->currency_type_id}}</td>
                                    <td class="celda">{{$value->relation_item->unit_type->name}}</td>
                                    <td class="celda">{{$value->relation_item->internal_id}}</td>
                                    <td class="celda">{{$value->relation_item->name}}</td>
                                    <td class="celda">{{$value->quantity}}</td>
                                    <td class="celda">{{$value->unit_price}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                    
                                </tr> 
                                @endforeach
                            @else
                            
                                @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$value->purchase->date_of_issue->format('Y-m-d')}}</td> 
                                    <td class="celda">{{$value->purchase->document_type->description}}</td>
                                    <td class="celda">{{$value->purchase->series}}</td>
                                    <td class="celda">{{$value->purchase->number}}</td>
                                    <td class="celda">{{$value->purchase->supplier->name}} - {{$value->purchase->supplier->number}}</td>
                                    <td class="celda">{{$value->purchase->currency_type_id}}</td>
                                    <td class="celda">{{$value->relation_item->unit_type->name}}</td>
                                    <td class="celda">{{$value->relation_item->internal_id}}</td>
                                    <td class="celda">{{$value->relation_item->name}}</td>
                                    <td class="celda">{{$value->quantity}}</td>
                                    <td class="celda">{{$value->unit_price}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                    
                                </tr> 
                                @endforeach
                            @endif
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
