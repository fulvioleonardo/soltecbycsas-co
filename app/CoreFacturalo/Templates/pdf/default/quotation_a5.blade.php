@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $accounts = \App\Models\Tenant\BankAccount::all();
    $tittle = $document->prefix.'-'.str_pad($document->id, 8, '0', STR_PAD_LEFT);
@endphp
<html>
<head>
    {{--<title>{{ $tittle }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>

<table class="full-width mt-5">
    <tr>
        <td width="15%">Cliente:</td>
        <td width="45%">{{ $customer->name }}</td>
        <td width="25%">Fecha de emisión:</td>
        <td width="15%">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>{{ $customer->identity_document_type->name }}:</td>
        <td>{{ $customer->number }}</td>

        @if($document->date_of_due)
            <td width="25%">Fecha de vencimiento:</td>
            <td width="15%">{{ $document->date_of_due->format('Y-m-d') }}</td>
        @endif
    </tr>
    @if ($customer->address !== '')
    <tr>
        <td class="align-top">Dirección:</td>
        <td>
            {{ $customer->address }}
            {{ ($customer->country_id)? ', '.$customer->country->name : '' }}
            {{ ($customer->department_id)? ', '.$customer->department->name : '' }}
            {{ ($customer->city_id)? '- '.$customer->city->name : '' }}
        </td>
        @if($document->delivery_date)
            <td width="25%">Fecha de entrega:</td>
            <td width="15%">{{ $document->delivery_date->format('Y-m-d') }}</td>
        @endif
    </tr>
    @endif

    @if ($document->payment_method_type)
    <tr>
        <td class="align-top">T. Pago:</td>
        <td colspan="">
            {{ $document->payment_method_type->description }}
        </td>
        @if($document->sale_opportunity)
            <td width="25%">O. Venta:</td>
            <td width="15%">{{ $document->sale_opportunity->number_full }}</td>
        @endif
    </tr>
    @endif

    @if ($document->account_number)
    <tr>
        <td class="align-top">N° Cuenta:</td>
        <td colspan="3">
            {{ $document->account_number }}
        </td>
    </tr>
    @endif
    @if ($document->shipping_address)
    <tr>
        <td class="align-top">Dir. Envío:</td>
        <td colspan="3">
            {{ $document->shipping_address }}
        </td>
    </tr>
    @endif
    @if ($customer->telephone)
    <tr>
        <td class="align-top">Teléfono:</td>
        <td colspan="3">
            {{ $customer->telephone }}
        </td>
    </tr>
    @endif

    <tr>
        <td class="align-top">Vendedor:</td>
        <td colspan="3">
            {{ $document->user->name }}
        </td>
    </tr>
</table>

<table class="full-width mt-3">
    @if ($document->description)
        <tr>
            <td width="15%" class="align-top">Descripción: </td>
            <td width="85%">{{ $document->description }}</td>
        </tr>
    @endif
</table>

{{-- <table class="full-width mt-3">
    @if ($document->purchase_order)
        <tr>
            <td width="25%">Orden de Compra: </td>
            <td>:</td>
            <td class="text-left">{{ $document->purchase_order }}</td>
        </tr>
    @endif
</table> --}}

@if ($document->guides)
<br/>
{{--<strong>Guías:</strong>--}}
<table>
    @foreach($document->guides as $guide)
        <tr>
            @if(isset($guide->document_type_description))
            <td>{{ $guide->document_type_description }}</td>
            @else
            <td>{{ $guide->document_type_id }}</td>
            @endif
            <td>:</td>
            <td>{{ $guide->number }}</td>
        </tr>
    @endforeach
</table>
@endif

<table class="full-width mt-10 mb-10">
    <thead class="">
    <tr class="bg-grey">
        <th class="border-top-bottom text-center py-2" width="8%">CANT.</th>
        <th class="border-top-bottom text-center py-2" width="8%">UNIDAD</th>
        <th class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>
        <th class="border-top-bottom text-right py-2" width="12%">P.UNIT</th>
        <th class="border-top-bottom text-right py-2" width="8%">DTO.</th>
        <th class="border-top-bottom text-right py-2" width="12%">TOTAL</th>
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
            <td class="text-center align-top">{{ $row->item->unit_type->name }}</td>
            <td class="text-left">
                {!!$row->item->name!!} @if (!empty($row->item->presentation)) {!!$row->item->presentation->description!!} @endif
                @if($row->attributes)
                    @foreach($row->attributes as $attr)
                        <br/><span style="font-size: 9px">{!! $attr->description !!} : {{ $attr->value }}</span>
                    @endforeach
                @endif
                @if($row->discounts)
                    @foreach($row->discounts as $dtos)
                        <br/><span style="font-size: 9px">{{ $dtos->factor * 100 }}% {{$dtos->description }}</span>
                    @endforeach
                @endif
            </td>
            <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right align-top">
                {{ number_format($row->discount, 2) }}
            </td>
            <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="6" class="border-bottom"></td>
        </tr>
    @endforeach


        <tr>
            <td colspan="5" class="text-right font-bold">TOTAL VENTA: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ $document->sale }}</td>
        </tr>
        <tr >
            <td colspan="5" class="text-right font-bold">TOTAL DESCUENTO (-): {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ $document->total_discount }}</td>
        </tr>

        @foreach ($document->taxes as $tax)
            @if ((($tax->total > 0) && (!$tax->is_retention)))
                <tr >
                    <td colspan="5" class="text-right font-bold">
                        {{$tax->name}}(+): {{ $document->currency->symbol }}
                    </td>
                    <td class="text-right font-bold">{{number_format($tax->total, 2)}} </td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="5" class="text-right font-bold">SUBTOTAL: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ $document->subtotal }}</td>
        </tr>

        <tr>
            <td colspan="5" class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width">
    {{-- <tr>
        <td width="65%" style="text-align: top; vertical-align: top;">
            <br>
            @foreach($accounts as $account)
                <p>
                <span class="font-bold">{{$account->bank->description}}</span> {{$account->currency->description}}
                <span class="font-bold">N°:</span> {{$account->number}}
                @if($account->cci)
                - <span class="font-bold">CCI:</span> {{$account->cci}}
                @endif
                </p>
            @endforeach
        </td>
    </tr> --}}
    <tr>
        {{-- <td width="65%">
            @foreach($document->legends as $row)
                <p>Son: <span class="font-bold">{{ $row->value }} {{ $document->currency->description }}</span></p>
            @endforeach
            <br/>
            <strong>Información adicional</strong>
            @foreach($document->additional_information as $information)
                <p>{{ $information }}</p>
            @endforeach
        </td> --}}
    </tr>
</table>
<br>
<table class="full-width">
<tr>
    <td>
    <strong>PAGOS:</strong> </td></tr>
        @php
            $payment = 0;
        @endphp
        @foreach($document->payments as $row)
            <tr><td>- {{ $row->payment_method_type->description }} - {{ $row->reference ? $row->reference.' - ':'' }} {{ $document->currency->symbol }} {{ $row->payment }}</td></tr>
            @php
                $payment += (float) $row->payment;
            @endphp
        @endforeach
        <tr><td><strong>SALDO:</strong> {{ $document->currency->symbol }} {{ number_format($document->total - $payment, 2) }}</td>
    </tr>

</table>
</body>
</html>
