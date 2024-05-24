<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Impuestos</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Impuestos</strong></h3>
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

            @inject('reportTax', 'App\Services\ReportTaxService')
            <div class="">
                <div class=" ">
                    @php
                        $sale_total = 0;
                        $total_discount = 0;

                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Fecha emisión</th>
                                <th>Cliente</th>

                                <th>Documento</th>
                                <th>Base</th>
                                <th>Descuento</th>

                                @foreach($taxTitles as $tt)
                                    <th>{{ $tt->name}}({{ $tt->rate}})</th>
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
                                        <div>
                                            {{$value->prefix}}{{$value->number}}
                                            @if($value->type_document_id != 1)
                                                ({{$value->prefix}}{{$value->number}})
                                            @endif
                                        </div>
                                    </td>

                                    <td>$ {{$value->total}}</td>
                                    <td>$ {{$value->total_discount}}</td>

                                    @foreach($taxTitles as $tax)
                                        <td >${{$reportTax->getTaxTotalBill($tax, $value->taxes)}}</td>
                                    @endforeach
                                </tr>

                                @php
                                    $sale_total += $value->sale;
                                    $total_discount += $value->total_discount;
                                @endphp
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"><strong>Totales:</strong></td>
                                <td><strong>${{ $sale_total }}</strong></td>
                                <td><strong>${{ $total_discount }}</strong></td>
                                @foreach($taxTitles as $tax)
                                    <td><strong>${{$reportTax->getTaxTotal($tax, $taxesAll)}}</strong></td>
                                @endforeach
                            </tr>
                        </tfoot>
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
