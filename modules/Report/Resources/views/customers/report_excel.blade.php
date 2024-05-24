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
            <h3 align="center" class="title"><strong>REPORTE POR CLIENTE</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                @if(!empty($records))
                    <tr>
                        <td>
                            <p><b>Cliente: </b></p>
                        </td>
                        <td align="center">
                            <p><strong>{{$records[0]->customer->number}} - {{$records[0]->customer->name}}</strong></p>
                        </td> 
                    </tr>
                @endif
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
                                <th class="">#</th>
                                <th class="">Fecha</th>
                                <th class="">Tipo Documento</th>
                                <th class="">Prefijo</th>
                                <th class="">Número</th>
                                <th class="">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td class="celda">{{$loop->iteration}}</td>
                                <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td> 
                                <td class="celda">{{$value->type_document->name}}</td>
                                <td class="celda">{{$value->series}}</td>
                                <td class="celda">{{$value->number}}</td>
                                <td class="celda">{{$value->total}}</td>
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
