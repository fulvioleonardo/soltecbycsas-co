@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $accounts = \App\Models\Tenant\BankAccount::all();
    $tittle = $document->prefix.'-'.str_pad($document->id, 8, '0', STR_PAD_LEFT);
    $sucursal = \App\Models\Tenant\Establishment::where('id', auth()->user()->establishment_id)->first();
    $filename_logo = "";
    if(!is_null($sucursal->establishment_logo)){
        if(file_exists(public_path('storage/uploads/logos/'.$sucursal->id."_".$sucursal->establishment_logo)))
            $filename_logo = public_path('storage/uploads/logos/'.$sucursal->id."_".$sucursal->establishment_logo);
        else
            $filename_logo = public_path("storage/uploads/logos/{$company->logo}");
    }
    else
        $filename_logo = public_path("storage/uploads/logos/{$company->logo}");
@endphp
<html>
<head>
    {{--<title>{{ $tittle }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>

@if($filename_logo != "")
    <div class="text-center company_logo_box pt-5">
        <img src="data:{{mime_content_type($filename_logo)}};base64, {{base64_encode(file_get_contents($filename_logo))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
    </div>
@endif
{{--@if($company->logo)
    <div class="text-center company_logo_box pt-5">
        <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo_ticket contain">
    </div>
@else--}}
    {{--<div class="text-center company_logo_box pt-5">--}}
        {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo_ticket contain">--}}
    {{--</div>
@endif--}}
<table class="full-width">
    <tr>
        <td class="text-center"><h5>{{ $company->name }}</h5></td>
    </tr>
    <tr>
        <td class="text-center"><h5>{{$company->identification_number }}</h5></td>
    </tr>
    <tr>
        <td class="text-center">
            {{ ($establishment->address !== '-')? $establishment->address : '' }}
            {{ ($establishment->city_id !== '-')? ', '.$establishment->city->name : '' }}
            {{ ($establishment->department_id !== '-')? '- '.$establishment->department->name : '' }}
            {{ ($establishment->country_id !== '-')? ', '.$establishment->country->name : '' }}

            @isset($establishment->trade_address)
                <h6>{{ ($establishment->trade_address !== '-')? 'D. Comercial: '.$establishment->trade_address : '' }}</h6>
            @endisset
            <h6>{{ ($establishment->telephone !== '-')? 'Central telefónica: '.$establishment->telephone : '' }}</h6>

            <h6>{{ ($establishment->email !== '-')? 'Email: '.$establishment->email : '' }}</h6>

            @isset($establishment->web_address)
                <h6>{{ ($establishment->web_address !== '-')? 'Web: '.$establishment->web_address : '' }}</h6>
            @endisset

            @isset($establishment->aditional_information)
                <h6>{{ ($establishment->aditional_information !== '-')? $establishment->aditional_information : '' }}</h6>
            @endisset
        </td>
    </tr>
    <tr>
        <td class="text-center">{{ ($establishment->email !== '-')? $establishment->email : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pb-3">{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</td>
    </tr>
    <tr>
        <td class="text-center pt-3 border-top"><h4>COTIZACIÓN</h4></td>
    </tr>
    <tr>
        <td class="text-center pb-3 border-bottom"><h3>{{ $tittle }}</h3></td>
    </tr>
</table>
<table class="full-width">
    <tr>
        <td width="" class="pt-3"><p class="desc">F. Emisión:</p></td>
        <td width="" class="pt-3"><p class="desc">{{ $document->date_of_issue->format('Y-m-d') }}</p></td>
    </tr>

    @if($document->date_of_due)
    <tr>
        <td width="" class=""><p class="desc">F. Vencimiento:</p></td>
        <td width="" class=""><p class="desc">{{ $document->date_of_due->format('Y-m-d') }}</p></td>
    </tr>
    @endif

    @if($document->delivery_date)
    <tr>
        <td width="" class=""><p class="desc">F. Entrega:</p></td>
        <td width="" class=""><p class="desc">{{ $document->delivery_date->format('Y-m-d') }}</p></td>
    </tr>
    @endif

    <tr>
        <td class="align-top"><p class="desc">Cliente:</p></td>
        <td><p class="desc">{{ $customer->name }}</p></td>
    </tr>
    <tr>
        <td><p class="desc">{{ $customer->identity_document_type->name }}:</p></td>
        <td><p class="desc">{{ $customer->number }}</p></td>
    </tr>
    @if ($customer->address !== '')
        <tr>
            <td class="align-top"><p class="desc">Dirección:</p></td>
            <td>
                <p class="desc">
                    {{ $customer->address }}
                    {{ ($customer->country_id)? ', '.$customer->country->name : '' }}
                    {{ ($customer->department_id)? ', '.$customer->department->name : '' }}
                    {{ ($customer->city_id)? '- '.$customer->city->name : '' }}
                </p>
            </td>
        </tr>
    @endif
    @if ($document->shipping_address)
    <tr>
        <td class="align-top"><p class="desc">Dir. Envío:</p></td>
        <td colspan="3">
            <p class="desc">
                {{ $document->shipping_address }}
            </p>
        </td>
    </tr>
    @endif

    @if ($customer->telephone)
    <tr>
        <td class="align-top"><p class="desc">Teléfono:</p></td>
        <td >
            <p class="desc">
                {{ $customer->telephone }}
            </p>
        </td>
    </tr>
    @endif
    @if ($document->payment_method_type)
    <tr>
        <td class="align-top"><p class="desc">T. Pago:</p></td>
        <td >
            <p class="desc">
                {{ $document->payment_method_type->description }}
            </p>
        </td>
    </tr>
    @endif

    @if ($document->account_number)
    <tr>
        <td class="align-top"><p class="desc">N° Cuenta:</p></td>
        <td colspan="">
            <p class="desc">
                {{ $document->account_number }}
            </p>
        </td>
    </tr>
    @endif
    @if ($document->sale_opportunity)
    <tr>
        <td class="align-top"><p class="desc">O. Venta:</p></td>
        <td >
            <p class="desc">
                {{ $document->sale_opportunity->number_full }}
            </p>
        </td>
    </tr>
    @endif
    <tr>
        <td class="align-top"><p class="desc">Vendedor:</p></td>
        <td>
            <p class="desc">
                {{ $document->user->name }}

            </p>
        </td>
    </tr>
    @if ($document->description)
        <tr>
            <td class="align-top"><p class="desc">Descripción:</p></td>
            <td><p class="desc">{{ $document->description }}</p></td>
        </tr>
    @endif
    @if ($document->purchase_order)
        <tr>
            <td><p class="desc">Orden de Compra:</p></td>
            <td><p class="desc">{{ $document->purchase_order }}</p></td>
        </tr>
    @endif
    @if ($document->quotation_id)
        <tr>
            <td><p class="desc">Cotización:</p></td>
            <td><p class="desc">{{ $document->quotation->identifier }}</p></td>
        </tr>
    @endif
</table>

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr>
        <th class="border-top-bottom desc-9 text-left">CANT.</th>
        <th class="border-top-bottom desc-9 text-left">UNIDAD</th>
        <th class="border-top-bottom desc-9 text-left">DESCRIPCIÓN</th>
        <th class="border-top-bottom desc-9 text-left">P.UNIT</th>
        <th class="border-top-bottom desc-9 text-left">TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @foreach($document->items as $row)
        <tr>
            <td class="text-center desc-9 align-top">
                @if(((int)$row->quantity != $row->quantity))
                    {{ $row->quantity }}
                @else
                    {{ number_format($row->quantity, 0) }}
                @endif
            </td>
            <td class="text-center desc-9 align-top">{{ $row->item->unit_type->name }}</td>
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
            <td class="text-right desc-9 align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right desc-9 align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5" class="border-bottom"></td>
        </tr>
    @endforeach

        <tr>
            <td colspan="4" class="text-right font-bold desc">TOTAL VENTA: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold desc">{{ $document->sale }}</td>
        </tr>
        <tr >
            <td colspan="4" class="text-right font-bold desc">TOTAL DESCUENTO (-): {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold desc">{{ $document->total_discount }}</td>
        </tr>

        @foreach ($document->taxes as $tax)
            @if ((($tax->total > 0) && (!$tax->is_retention)))
                <tr >
                    <td colspan="4" class="text-right font-bold desc">
                        {{$tax->name}}(+): {{ $document->currency->symbol }}
                    </td>
                    <td class="text-right font-bold desc">{{number_format($tax->total, 2)}} </td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="4" class="text-right font-bold desc">SUBTOTAL: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold desc">{{ $document->subtotal }}</td>
        </tr>

        <tr>
            <td colspan="4" class="text-right font-bold desc">TOTAL A PAGAR: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold desc">{{ number_format($document->total, 2) }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width">
    <tr>

        @foreach(array_reverse((array) $document->legends) as $row)
            <tr>
                @if ($row->code == "1000")
                    <td class="desc pt-3">Son: <span class="font-bold">{{ $row->value }} {{ $document->currency->description }}</span></td>
                    @if (count((array) $document->legends)>1)
                    <tr><td class="desc pt-3"><span class="font-bold">Leyendas</span></td></tr>
                    @endif
                @else
                    <td class="desc pt-3">{{$row->code}}: {{ $row->value }}</td>
                @endif
            </tr>
        @endforeach
    </tr>

    {{-- <tr>
        <td class="desc pt-3">
            <br>
            @foreach($accounts as $account)
                <span class="font-bold">{{$account->bank->description}}</span> {{$account->currency->description}}
                <br>
                <span class="font-bold">N°:</span> {{$account->number}}
                @if($account->cci)
                - <span class="font-bold">CCI:</span> {{$account->cci}}
                @endif
                <br>
            @endforeach

        </td>
    </tr> --}}

</table>
<br>
<table class="full-width">
<tr>
    <td class="desc pt-3">
    <strong>PAGOS:</strong> </td></tr>
        @php
            $payment = 0;
        @endphp
        @foreach($document->payments as $row)
            <tr><td class="desc ">- {{ $row->payment_method_type->description }} - {{ $row->reference ? $row->reference.' - ':'' }} {{ $document->currency->symbol }} {{ $row->payment }}</td></tr>
            @php
                $payment += (float) $row->payment;
            @endphp
        @endforeach
        <tr><td class="desc pt-3"><strong>SALDO:</strong> {{ $document->currency->symbol }} {{ number_format($document->total - $payment, 2) }}</td>
    </tr>

    @if($document->terms_condition)
    <tr>
        <td class="text-center desc pt-5 font-bold">{{$document->terms_condition}}</td>
    </tr>
    @endif
</table>
</body>
</html>
