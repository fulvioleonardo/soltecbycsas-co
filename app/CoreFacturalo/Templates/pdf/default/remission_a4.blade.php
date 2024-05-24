@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $paymentForm = $document->payment_form;
    $tittle = $document->prefix.'-'.str_pad($document->id, 8, '0', STR_PAD_LEFT);
@endphp
<html>
<head>
</head>
<body  margin-top:50px>

    <table width="100%">
        <tr>
            <td style="width: 25%;" class="text-center vertical-align-top">
                <div id="reference">
                    <p style="font-weight: 700;"><strong>REMISIÓN No</strong></p>
                    <br>
                    <p style="color: red;
                        font-weight: bold;
                        font-size: 14px;
                        margin-bottom: 8px;
                        border: 1px solid #000;
                        padding: 5px 8px;
                        line-height: 1;
                        display: inline-block;
                        border-radius: 6px;">{{ $document->number_full }}</p>
                        <br>
                    <p style="color: red;
                        font-weight: bold;
                        font-size: 11px;
                        margin-bottom: 8px;
                        border: 1px solid #000;
                        padding: 5px 8px;
                        line-height: 1;
                        display: inline-block;
                        border-radius: 6px;">Fecha Emision: {{ $document->date_of_issue->format('Y-m-d') }}</p>
                        <br>
                </div>
            </td>
            <td style="width: 50%; padding: 0 1rem;" class="text-center vertical-align-top">
                <div id="empresa-header">
                    <strong>{{$company->name}}</strong><br>
                    <strong>{{$establishment->description}}</strong><br>
                </div>
                <div id="empresa-header1">
                    
                    NIT: {{$company->identification_number}} - {{$company->type_regime->name}}
                    
                    Tipo Documento ID: {{$company->type_identity_document->name}}<br>

                    TARIFA ICA: {{$company->ica_rate}}%

                    @if($company->economic_activity_code)
                        ACTIVIDAD ECONOMICA: {{$company->economic_activity_code}}<br>
                    @else
                        <br>
                    @endif
                    
                    {{$establishment->address != '-' ? $establishment->address : $company->address}} - {{$establishment->city->name ?? ''}} - {{$establishment->department->name ?? ''}} - {{$establishment->country->name ?? ''}}

                    Telefono - {{$establishment->telephone}}<br>
                    E-mail: {{$establishment->email}} <br>
                </div>
            </td>
            <td style="width: 25%; text-align: right;" class="vertical-align-top">
                @if ($company->logo)
                <img style="width: 136px; height: auto;" src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" alt="{{ $company->name }}" class="company_logo" style="max-width: 200px">
                @endif
            </td>
        </tr>
    </table>
    

    <table style="font-size: 10px; margin-top: 20px">
        <tr>
            <td class="vertical-align-top" style="width: 30%;">
                <table>
                    <tr>
                        <td>CC o NIT:</td>
                        <td>{{$customer->number}}-{{$customer->dv}} </td>
                    </tr>
                    <tr>
                        <td>Cliente:</td>
                        <td>{{$customer->name}}</td>
                    </tr>
                    <tr>
                        <td>Regimen:</td>
                        <td>{{$customer->type_regime->name}}</td>
                    </tr> 
                    <tr>
                        <td>Dirección:</td>
                        <td>{{$customer->address}}</td>
                    </tr>
                    <tr>
                        <td>Ciudad:</td>
                        <td>{{$customer->city ? $customer->city->name : ''}} {{$customer->country ? ' - '.$customer->country->name : ''}} </td>
                    </tr>
                    <tr>
                        <td>Telefono:</td>
                        <td>{{$customer->telephone}}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{$customer->email}}</td>
                    </tr>
                </table>
            </td>
            <td class="vertical-align-top" style="width: 40%; padding-left: 1rem">
                <table>
                    <tr>
                        <td>Forma de Pago:</td>
                        <td>{{$paymentForm->name}}</td>
                    </tr>
                    <tr>
                        <td>Medio de Pago:</td>
                        <td>{{$document->payment_method->name}}</td>
                    </tr>
                    @if($document->time_days_credit)
                    <tr>
                        <td>Plazo Para Pagar:</td>
                        <td>{{$document->time_days_credit}} Dias</td>
                    </tr>
                    @endif
                </table>
            </td> 
        </tr>
    </table>
    <br> 
    <table class="table" style="width: 100%;">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Código</th>
                <th class="text-center">Descripcion</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">UM</th>
                <th class="text-center">Val. Unit</th>
                <th class="text-center">IVA/IC</th>
                <th class="text-center">Dcto</th>
                <th class="text-center">%</th>
                <th class="text-center">Val. Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->items as $row)
                <tr>
                    
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$row->item->internal_id}}</td>
                    <td>
                        {{$row->item->name}} @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif
                    </td>
                    <td class="text-right">{{number_format($row->quantity, 2)}}</td>

                    <td class="text-right">{{ $row->item->unit_type->name }}</td>
                    
                    <td class="text-right">{{number_format($row->unit_price, 2)}}</td>
                    
                    <td class="text-right">{{number_format($row->total_tax / $row->quantity, 2)}}</td>

                    <td class="text-right">{{number_format($row->discount, 2)}}</td>
                    <td class="text-right">{{number_format(($row->discount * 100) / $row->total, 2)}}</td>
                    <td class="text-right">{{number_format($row->total, 2)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <table class="table" style="width: 40%" align="right">
        <thead>
            <tr>
                <th class="text-right">Totales</th>
            </tr>
        </thead>
        <tbody>
            <tr>  
                <td style="width: 30%;">
                    <table class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center">Concepto</th>
                                <th class="text-center">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nro Lineas:</td>
                                <td>{{$document->items->count()}}</td>
                            </tr>
                            <tr>
                                <td>Base:</td>
                                <td>{{number_format($document->sale, 2)}}</td>
                            </tr>
                            <tr>
                                <td>Impuestos:</td>
                                <td>{{number_format($document->total_tax, 2)}}</td>
                            </tr>
                            <tr>
                                <td>Descuentos:</td>
                                <td>{{number_format($document->total_discount, 2)}}</td>
                            </tr>
                            <tr>
                                <td>Total Factura:</td>
                                <td>{{number_format($document->total, 2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="summarys">
        <div class="text-word" id="note">
            <p><strong>OBSERVACIONES:</strong></p>
            <br>
            <p>{{$document->observation }}</p>
        </div>
    </div>

    <div class="summary" >
        <div class="text-word" id="note">
            <p style="font-style: italic;">INFORME EL PAGO AL TELEFONO {{$establishment->telephone}} o al e-mail {{$establishment->email}}<br>
            </p>
        </div>
    </div>
    
</body>
</html>
