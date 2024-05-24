@php
    use Mpdf\QrCode\QrCode;
    use Mpdf\QrCode\Output;

    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $tittle = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $payments = $document->payments;
    $is_epos = $document->electronic === 1 ? true : false;

    // $config_pos = \App\Models\Tenant\ConfigurationPos::first();
    // $user = auth()->user();

    // $cash = \App\Models\Tenant\Cash::where('state', 1)
    //                     ->where('user_id', $user->id)
    //                     ->first();

    // $resolution = $cash->resolution;

    $resolution = $document->getCashResolution();
    $payment = 0;

    $sucursal = \App\Models\Tenant\Establishment::where('id', $document->establishment_id)->first();
    if(!is_null($sucursal->establishment_logo)){
        if(file_exists(public_path('storage/uploads/logos/'.$sucursal->id."_".$sucursal->establishment_logo)))
            $filename_logo = public_path('storage/uploads/logos/'.$sucursal->id."_".$sucursal->establishment_logo);
        else
            $filename_logo = public_path("storage/uploads/logos/{$company->logo}");
    }
    else
        $filename_logo = public_path("storage/uploads/logos/{$company->logo}");

    if($is_epos) {
        $data_qr = $document->qr;
        $codigoQR = new QrCode($data_qr);
        $output = new Output\Png();
        $imagenCodigoQR = $output->output($codigoQR, 90);
    }
@endphp
<html>
<head>

</head>
<body>

@if($filename_logo != "")
    <div class="text-center company_logo_box">
        <img src="data:{{mime_content_type($filename_logo)}};base64, {{base64_encode(file_get_contents($filename_logo))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
    </div>
@endif
<table class="full-width">
    <tr>
        <td colspan="2" class="text-center"><h4>{{ $company->name }}</h4></td>
    </tr>
    <tr>
        <td colspan="2"><h5 >Nit: {{ $company->identification_number }} - {{ $company->type_regime->name}} </h5></td>
    </tr>
    <tr>
        <td colspan="2"><h5>{{ ($establishment->email !== '-') ? $establishment->email : '' }}</h6></td>
    </tr>
    <tr>
        <td><h6>{{ $sucursal->description }}</h6></td>
    </tr>
    <br>
    <tr>
        @if($is_epos)
            <td colspan="2"> <h6>DOCUMENTO EQUIVALENTE ELECTRONICO DEL TIQUETE DE MAQUINA REGISTRADORA CON SISTEMA P.O.S. No: {{ $tittle }}</h6> </td>
        @else
            <td colspan="2"> <h6>Documento Equivalente POS #: {{ $tittle }}</h6> </td>
        @endif
    </tr>
    <br>
    @if($is_epos)
        <?php
            if(is_string($document->request_api))
                $request_api = json_decode($document->request_api, true);
            else
                $request_api = json_decode(json_encode($document->request_api), true);
        ?>
        <tr>
            <td><h6>Serial de caja: {{ $request_api['cash_information']['plate_number'] }}</h6></td>
            <td class=""><h6>Tipo de caja: {{ $request_api['cash_information']['cash_type'] }}</h6></td>
        </tr>
        <tr>
            <td><h6>Cajero:  {{ $request_api['cash_information']['cashier'] }} </h6></td>
        </tr>
    @endif
    <tr>
        <td><h6>Fecha: {{ $document->date_of_issue->format('d-m-Y')}}</h6></td>
        <td><h6>Vence: {{$document->date_of_issue->format('d-m-Y') }}</h6></td>
    </tr>
    <tr>
        <td><h6>Cliente:{{ $customer->name }}</h6></td>
        <td><h6>Ciudad: {{ ($customer->city_id)? ''.$customer->city->name : '' }}</h6></td>
    </tr>
    <tr>
        <td colspan="2"><h6>{{$customer->identity_document_type->name}}: {{ $customer->code }}</h6></td>
    </tr>
    <tr>
        <td colspan="2"> <h6>Direccion: {{ $customer->address }} </h6></td>
    </tr>
    <tr>
        <td> <h6>Tipo Venta: CONTADO 0 días </h6></td>
    </tr>
</table>
<table class="full-width">
    <thead class="">
    <tr>
        <th class="border-top-bottom desc-9 text-left">CANT.</th>
        <th class="border-top-bottom desc-9 text-left">CODIGO</th>
        <th class="border-top-bottom desc-9 text-left">DESCRIPCIÓN</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center align-top">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="desc-9 align-top"> {{ $row->item->internal_id }}</td>
            <td class="text-left desc-9 align-top">
                {!!$row->item->name!!} @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/>{!! $attr->description !!} : {{ $attr->value }}
                    @endforeach
                @endif
                @if($row->discount > 0)
                <br>
                {{ $row->discount }}
                @endif
            </td>
        </tr>

        <table class="full-width">
            <tbody>
                <tr>
                    <td class="text-left desc-9 align-top">
                        {{ number_format($row->unit_price, 2)}}
                    </td>
                    <td class="text-left desc-9 align-top">
                        {{ number_format($row->total_tax, 2)}}
                    </td>
                    <td class="text-right desc-9 align-top">
                        {{ number_format($row->subtotal, 2)}}
                    </td>
                </tr>
            </tbody>
        </table>
        <tr>
            <td colspan="3" class="border-bottom"></td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="full-width">
    <tr>
        <td colspan="2" class="text-right font-bold desc">TOTAL VENTA: {{ $document->currency->symbol }}</td>
        <td class="text-right font-bold desc">{{ $document->sale }}</td>
    </tr>
    <tr >
        <td colspan="2" class="text-right font-bold desc">TOTAL DESCUENTO (-): {{ $document->currency->symbol }}</td>
        <td class="text-right font-bold desc">{{ $document->total_discount }}</td>
    </tr>
    <tr>
        <td colspan="2" class="text-right font-bold desc">SUBTOTAL: {{ $document->currency->symbol }}</td>
        <td class="text-right font-bold desc">{{ number_format($document->subtotal - $document->total_tax, 2) }}</td>
    </tr>
    @foreach ($document->taxes as $tax)
        @if ((($tax->total > 0) && (!$tax->is_retention)))
            <tr >
                <td colspan="2" class="text-right font-bold desc">
                    {{$tax->name}}(+): {{ $document->currency->symbol }}
                </td>
                <td class="text-right font-bold desc">{{number_format($tax->total, 2)}} </td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td colspan="2" class="text-right font-bold desc">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
        <td class="text-right font-bold desc">{{ number_format($document->total, 2) }}</td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td class="desc">
            <span>PAGOS:</span><br>
            <ul>
                @foreach($payments as $row)
                    <li>{{ $row->date_of_payment->format('d/m/Y') }} {{ $row->payment_method_type->description }} {{ $row->reference ? $row->reference.' - ':'' }} {{ $document->currency_type->symbol }}{{ $row->payment }}</li>
                    @php
                        $payment += (float) $row->payment;
                    @endphp
                @endforeach
            </ul>
            <span>SALDO: {{ $document->currency_type->symbol }} {{ number_format($document->total - $payment, 2) }}</span>
            @if($resolution)
                <br>
                <span>Resol. DIAN #:{{ $resolution->resolution_number }}</span>
                <br>
                <span>Fecha resol.: {{ $resolution->resolution_date->format('d-m-Y') }}</span>
                <br>
                <span>Desde la Factura {{ $resolution->from }} a la {{ $resolution->to }}</span>
                <br>
                @php
                    $firstDate  = new \DateTime($resolution->date_from);
                    $secondDate = new \DateTime($resolution->date_end);
                    $intvl = $firstDate->diff($secondDate);
                @endphp
                <span>Vigencia: {{($intvl->y * 12) + $intvl->m}} Meses</span>
            @endif

        </td>
        @if($is_epos)
            <td>
                <img src="data:image/png;base64,{{ base64_encode($imagenCodigoQR) }}" alt="QR" style="margin-right: -20px;">
            </td>
        @endif
    </tr>
</table>
<table class="full-width">
    <tr>
        @if($document->state_type_id == '11')
            <td class="text-center">
                <h6>ANULADO</h6>
            </td>
        @else
            <td class="text-center">
                <h6>GRACIAS POR SU COMPRA</h6>
                @if($is_epos)
                    <tr>
                        <td><h6>Software: {{ $request_api['software_manufacturer']['software_name'] }}</h6></td>
                    </tr>
                    <tr>
                        <td><h6>Fabricante: {{ $request_api['software_manufacturer']['name'] }}</h6></td>
                    </tr>
                    <tr>
                        <td><h6>Compañia: {{ $request_api['software_manufacturer']['business_name'] }}</h6></td>
                    </tr>
                @endif
            </td>
        @endif
    </tr>
</table>
@if($is_epos)
    <p>cude: {{ $document->cude }}</p>
@endif
</body>
</html>
