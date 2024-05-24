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
        @if(!empty($records))
            <div class="">
                <div class=" "> 
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">Código tipo de persona
                                </th>
                                <th class="">Código tipo de régimen
                                </th>
                                <th class="">Código tipo de documento de identidad
                                </th>
                                <th class="">Número de identificación
                                </th>
                                <th class="">DV
                                </th>
                                <th class="">Código Interno
                                </th>
                                <th class="">Nombre completo
                                </th>
                                <th class="">Código de país
                                </th>
                                <th class="">Código de departamento
                                </th>
                                <th class="">Código de ciudad
                                </th>
                                <th class="">Dirección
                                </th>
                                <th class="">Teléfono
                                </th>
                                <th class="">Correo electrónico

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td>{{$value->type_person_id}}</td>
                                <td>{{$value->type_regime_id}}</td>
                                <td>{{$value->identity_document_type_id}}</td>
                                <td>{{$value->number}}</td>
                                <td>{{$value->dv}}</td>
                                <td>{{$value->code}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->country_id}}</td>
                                <td>{{$value->department_id}}</td>
                                <td>{{$value->city_id}}</td>
                                <td>{{$value->address}}</td>
                                <td>{{$value->telephone}}</td>
                                <td>{{$value->email}}</td>
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
