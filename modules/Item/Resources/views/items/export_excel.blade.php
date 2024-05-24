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
                                <th class="">Nombre</th>
                                <th class="">Código Interno </th>
                                <th class="">Código Tipo de Unidad</th>
                                <th class="">Código Tipo de Moneda</th>
                                <th class="">Precio Unitario Venta</th>
                                <th class="">Codigo Tipo de Impuesto Venta</th>
                                <th class="">Precio Unitario Compra</th>
                                <th class="">Codigo Tipo de Impuesto Compra</th>
                                <th class="">Categoria</th>
                                <th class="">Marca</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td>{{$value->name}}</td>
                                <td>{{$value->internal_id}}</td>
                                <td>{{$value->unit_type_id}}</td>
                                <td>{{$value->currency_type_id}}</td>
                                <td>{{$value->sale_unit_price}}</td>
                                <td>{{$value->tax_id}}</td>
                                <td>{{$value->purchase_unit_price}}</td>
                                <td>{{$value->purchase_tax_id}}</td>
                                <td>{{optional($value->category)->name}}</td>
                                <td>{{optional($value->brand)->name}}</td>
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
