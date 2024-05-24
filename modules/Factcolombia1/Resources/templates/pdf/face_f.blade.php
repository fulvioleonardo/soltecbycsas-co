<html>
    <head>
        <title>{{$document->prefix}}{{$document->number}}</title>
        <title></title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 12px;
            }
            
            .text-center {
                text-align: center;
            }
            
            .text-right {
                text-align: right;
            }
            
            .font-xxsm {
                font-size: 8px;
            }
            
            .font-xsm {
                font-size: 10px;
            }
            
            .font-sm {
                font-size: 12px;
            }
            
            .font-lg {
                font-size: 13px;
            }
            
            .font-xlg {
                font-size: 16px;
            }
            
            .font-xxlg {
                font-size: 22px;
            }
            
            .font-bold {
                font-weight: bold;
            }
            
            table {
                width: 100%;
                border-spacing: 0;
            }
            
            .voucher-company-right {
                /* border: 1px solid #333; */
                /*padding-top: 15px;*/
                padding-bottom: 15px;
                margin-bottom: 10px;
            }
            
            .voucher-company-right tbody tr:first-child td {
                /*padding-top: 10px;*/
            }
            
            .voucher-company-right tbody tr:last-child td {
                padding-bottom: 10px;
            }
            
            .voucher-information {
                border: 1px solid #333;
            }
            
            .voucher-information.top-note,
            .voucher-information.top-note tbody tr td {
                border-top: 0;
            }
            
            .voucher-information tbody tr td {
                padding-top: 5px;
                padding-bottom: 5px;
                vertical-align: top;
            }
            
            .voucher-information-left tbody tr td {
                padding: 3px 7px;
                vertical-align: top;
            }
            
            .voucher-information-right tbody tr td {
                padding: 3px 10px;
                vertical-align: top;
            }
                
            .voucher-details thead {
                background-color: #f5f5f5;
            }
            
            .voucher-details thead tr th {
                border-top: 2px solid #333;
                border-bottom: 2px solid #333;
                padding: 5px 10px;
            }
            
            .voucher-details thead tr th:first-child {
                border-left: 1px solid #333;
            }
            
            .voucher-details thead tr th:last-child {
                border-right: 1px solid #333;
            }
            
            .voucher-details tbody tr td {
                border-bottom: 1px solid #333;
            }
            
            .voucher-details tbody tr td:first-child {
                border-left: 1px solid #333;
            }
            
            .voucher-details tbody tr td:last-child {
                border-right: 1px solid #333;
            }
            
            .voucher-details tbody tr td {
                padding: 5px 10px;
                vertical-align: middle;
            }
            
            .voucher-details tfoot tr td {
                padding: 3px 10px;Dirección: No tiene
            }
            
            .voucher-totals {
                margin-top: 10px;
                margin-bottom: 10px;
            }
            
            .voucher-totals tbody tr td {
                padding: 3px 10px;
                vertical-align: top;
            }
            
            .voucher-footer {
                margin-bottom: 30px;
            }
            Dirección: No tiene
            .voucher-footer tbody tr td {
                border-top: 1px solid #333;
                padding: 3px 10px;
            }
            
            .company_logo {
                min-width: 150px;
                max-width: 100%;
                height: auto;
            }
            
            .pt-1 {
                padding-top: 1rem;
            }
        </style>
    </head>
    <body>
        <table class="voucher-company">
            <tr>
                <td width="20%" style="vertical-align: top;text-align: left;">
                    <img src="{{asset("/storage/uploads/logos/{$company->logo}")}}" style="max-height: 150px; position: left;">
                </td>
                <td width="55%" style="vertical-align: top;">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-left font-xlg font-bold" colspan="2">{{$company->name}}</td>
                            </tr>
                            <tr>
                                <td class="text-left font-xl" colspan="2"><strong>{{$servicecompany->type_document_identification->name}}:</strong> {{$servicecompany->identification_number}} - {{$servicecompany->dv}} </td>
                            </tr>
                            <tr>
                                <td class="text-left font-lg" colspan="2"><strong>Dirección:</strong> {{$servicecompany->address}}</td>
                            </tr>
                            <tr>
                                <td class="text-left font-lg" colspan="2"><strong>Correo electronico:</strong> {{$company->email}}</td>
                            </tr>
                            <tr>
                                <td class="text-left font-lg"><strong>Telefono:</strong>{{$servicecompany->phone}}</td>
                                <td class="text-left font-lg"><strong>Régimen:</strong> {{$servicecompany->type_regime->name}}</td>
                            </tr>
                            <tr>
                                <td class="text-left font-lg"><strong>Actividad Principal:</strong> {{$company->economic_activity_code}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-left font-lg"><strong>Impuesto:</strong> {{$servicecompany->tax->name}}  </td>
                                <td class="text-left font-lg"> <strong>ICA:</strong> {{$company->ica_rate}} /1000</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="25%">
                    <table class="voucher-company-right">
                        <tbody>
                            <tr>
                                <td class="text-center font-lg font-bold">{{strtoupper($document->type_document->name)}}</td>
                            </tr>
                            <tr>
                                <td class="text-center font-xlg font-bold">FE {{$document->prefix}}{{$document->number}}</td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <img class="not-padding-and-margin" width="25%" src="data:image/png;base64, {{base64_encode(QrCode::encoding('UTF-8')->format('png')->size(100)->generate("NumFac: {$document->prefix}{$document->number}\nFecFac: ".Carbon\Carbon::parse($document->date_issue)->format('YmdHms')."\nNitFac: {$company->identification_number}\nDocAdq: {$document->client->identification_number}\nValFac: {$document->sale}\nValimp: {$document->total_tax}\nValFacIm: {$document->total}\nCUFE: {$document->cufe}"))}}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <p class="font-xsm">
            DIAN Resolución N° {{  $servicecompany->resolution_model_api->resolution}}. 
             Fecha de expedición {{$servicecompany->resolution_model_api->date_to}}. 
            Númeración del {{$servicecompany->resolution_model_api->from}} al {{$servicecompany->resolution_model_api->to}}. 
            Vigencia: {{$servicecompany->resolution_model_api->vigencia}} MESES 
            Prefijo {{$servicecompany->resolution_model_api->prefix}}.
            
          
        </p>
        <table class="voucher-information">
            <tr>
                <td>
                    <table class="voucher-information-left">
                        <tbody>
                            <tr>
                              
                                <td width="50%"><strong>Fecha:</strong> {{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d H:m:s')}}</td>
                                <td width="50%"><strong>Fecha de vencimiento:</strong> {{Carbon\Carbon::parse($document->date_expiration)->format('Y-m-d')}}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>Fecha y Hora Expedicion de Validacion:</strong> {{$document->response_api_invoice_status_date_valid}} </td>
                                <td width="50%"></td>
                            </tr>
                            <tr>
                                
                                <td width="50%"><strong>Cliente:</strong> {{$document->client->name}} </td>
                                <td width="50%"><strong>{{$typeIdentityDocuments->where('id', $document->client->type_identity_document_id)->first()->name}}:</strong> {{$document->client->identification_number}} / {{$document->client->dv}}</td>
                            </tr>
                            <tr>
                               
                                <td width="50%"><strong>Correo electronico:</strong> {{$document->client->email}}</td>
                                <td width="50%"><strong>Dirección:</strong> {{$document->client->address}}</td>
                               
                            </tr>
                            <tr>
                               
                                <td width="50%"><strong>Regimen:</strong> {{$document->client->type_regime->name}}</td>
                                <td width="50%"><strong>Forma de Pago:</strong>  {{$document->payment_form->name}}</td>
                               
                            </tr>
                            <tr>
                                 
                                 <td width="50%"><strong>Medio de Pago:</strong> {{$document->payment_method->name}}</td>
                                 <td width="50%"><strong>Plazo:</strong> {{$document->plazo}} días</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <p colspan="2" width="100%"><strong>Observación:</strong> {{$document->observation}}</p>
      
        <table class="voucher-details">
            <thead>
                <tr>
                    <th class="text-center">CANTIDAD</th>
                    <th class="text-center">UNIDAD</th>
                    <th class="text-center">CODIGO</th>
                    <th class="text-center">DESCRIPCIÓN</th>

                    <th class="text-right">P.UNITARIO</th>
                    <th class="text-right">IMPUESTOS</th>
                    <th class="text-right">DESCUENTO</th>

                    <th class="text-right">IVA</th>
                    <th class="text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document->detail_documents as $item)
                    <tr>
                        <td class="text-center">{{number_format($item->quantity, 2, '.', ',')}}</td>
                        <td class="text-center">{{$item->item->type_unit->name}}</td>
                        <td class="text-center">{{$item->item->code}}</td>
                        <td class="text-center">{{$item->item->name}}</td>

                        <td class="text-right">{{$company->currency->symbol}}{{number_format($item->price, 2, '.', ',')}}</td>
                        <td class="text-right">{{$company->currency->symbol}}{{number_format($item->total_tax, 2, '.', ',')}}</td>
                        <td class="text-right">{{$company->currency->symbol}}{{number_format($item->discount, 2, '.', ',')}}</td>

                        <td class="text-right">{{$company->currency->symbol}}{{number_format($item->tax->rate, 2, '.', ',')}}</td>
                        <td class="text-right">{{$company->currency->symbol}}{{number_format($item->total, 2, '.', ',')}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="border-top: 1px solid #333;">
            </tfoot>
        </table>
        <table class="voucher-totals">
            <tbody>
                <tr>
                    <td width="65%">
                        <table class="voucher-totals-right">
                            <tbody>
                                <tr>
                                    <td class="text-right font-lg font-bold">TOTAL VENTA: {{$company->currency->symbol}}{{number_format($document->sale, 2, '.', ',')}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right font-lg font-bold">TOTAL DESCUENTO(-): {{$company->currency->symbol}}{{number_format($document->total_discount, 2, '.', ',')}}</td>
                                </tr>
                                @foreach ($document->taxes as $tax)
                                    @if ($tax->total > 0)
                                        <tr>
                                            <td class="text-right font-lg font-bold">{{$tax->name}}(+): {{$company->currency->symbol}}{{number_format($tax->total, 2, '.', ',')}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @foreach ($document->taxes as $tax)
                                    @if ($tax->apply)
                                        <tr>
                                            <td class="text-right font-lg font-bold">{{$tax->name}}(-): {{$company->currency->symbol}}{{number_format($tax->retention, 2, '.', ',')}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <tr>
                                        <td class="text-right font-xxlg font-bold">TOTAL: {{$company->currency->symbol}}{{number_format($document->total, 2, '.', ',')}}</td>
                                    </tr>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="voucher-footer">
            <tbody>
                <tr>
                    <td class="text-center font-sm">Cufe: {{$document->cufe}}</td>
                </tr>
                <tr>
                    <td class="text-center font-xxsm">VENCIDO EL PLAZO DE LA PRESENTE FACTURA CAUSARA EL MAXIMO INTERES DE MORA PERMITIDO POR LA LEY</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>