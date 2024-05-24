<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
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
            tr {
                background-color: #dddddd;
            }

            .leftx{
                margin-left: 4%;
            }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Informe Fiscal</strong></p>
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
            @inject('report', 'App\Services\TaxReportService')

            @php
                $excento = $report->getTotalExcento($items);

                $sale_total_iva5 = $report->getTotalIva5($items, true);
                $sale_total_iva19 = $report->getTotalIva19($items, true);
                $sale_total_ic8= $report->getTotalIC8($items, true);

                $base_imp_iva5 = $report->getTotalIva5($items);
                $base_imp_iva19 = $report->getTotalIva19($items);
                $base_imp_ic8 = $report->getTotalIC8($items);

                $discount = $report->getDiscounts($items);

                $total_iva = ( ($sale_total_iva5 + $sale_total_iva19 ) - (  $base_imp_iva5 + $base_imp_iva19 ) );
                $total_ic = ( $sale_total_ic8 - $base_imp_ic8);

                $total_desc = ($total_sale - $discount);


            @endphp
            <div >

                <table class="">

                        <tr>
                            <td>Venta Total: {{ $total_sale }} </td>
                        </tr>
                        <tr>
                            <td> <span class="leftx"> Excento: {{ number_format($excento, 2) }} </span>  </td>
                        </tr>

                        <tr >
                            <td> <span class="leftx">Grav IVA 5: {{ number_format( $sale_total_iva5, 2) }}</span></td>
                        </tr>
                        <tr>
                            <td> <span class="leftx">Grav IVA 19: {{ number_format($sale_total_iva19, 2) }} </span>  </td>
                        </tr>
                        <tr>
                            <td>  <span class="leftx">Grav I.C 8: {{ number_format($sale_total_ic8, 2) }} </span>  </td>
                        </tr>

                        <tr>
                            <td>Base Imp. : {{ number_format($total_sale_base, 2) }}</td>
                        </tr>
                        <tr>
                            <td> <span class="leftx"> Excento: {{ number_format($excento, 2) }} </span>  </td>
                        </tr>

                        <tr>
                            <td>  <span class="leftx"> Grav IVA 5: {{ number_format( $base_imp_iva5, 2) }}</span>  </td>
                        </tr>

                        <tr>
                            <td>  <span class="leftx"> Grav IVA 19: {{ number_format($base_imp_iva19, 2) }}</span> </td>
                        </tr>

                        <tr>
                            <td> <span class="leftx"> Grav I.C 8: {{ number_format($base_imp_ic8, 2) }}</span>  </td>
                        </tr>



                </table>
                <br>
                <table>
                    <tr>
                        <td> <span class="">Total Iva: {{ $total_iva }}</span>  </td>
                    </tr>

                    <tr>
                        <td> <span class=""> Total IC: {{ $total_ic }}</span>  </td>
                    </tr>

                    <tr>
                        <td> <span class=""> Total Descuento: {{ $discount }}</span>  </td>
                    </tr>

                    <tr>
                        <td> <span class=""> Total - Desc.: {{ $total_desc}}</span>  </td>
                    </tr>
                </table>
            </div>
    </body>
</html>
