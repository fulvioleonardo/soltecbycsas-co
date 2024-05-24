@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');

    $left =  ($document->series) ? $document->series : $document->prefix;
    $tittle = $left.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $payments = $document->payments;

@endphp
<html>
<head>
    {{--<title>{{ $tittle }}</title>--}}
    {{--<link href="{{ $path_style }}" rel="stylesheet" />--}}
</head>
<body>
<table class="full-width">
    <tr>
        @if($company->logo)
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type(public_path("storage/uploads/logos/{$company->logo}"))}};base64, {{base64_encode(file_get_contents(public_path("storage/uploads/logos/{$company->logo}")))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
                </div>
            </td>
        @else
            <td width="20%">
            </td>
        @endif
        <td width="50%" class="pl-3">
            <div class="text-left">
                <h4 class="">{{ $company->name }}</h4>
                <h5>{{ $company->identification_number }}</h5> 
                <h6>
                    {{ ($establishment->address !== '-')? $establishment->address : '' }}
                    {{ ($establishment->city_id !== '-')? ', '.$establishment->city->name : '' }}
                    {{ ($establishment->department_id !== '-')? '- '.$establishment->department->name : '' }}
                    {{ ($establishment->country_id !== '-')? ', '.$establishment->country->name : '' }}
                </h6>
                <h6>{{ ($establishment->email !== '-')? $establishment->email : '' }}</h6>
                <h6>{{ ($establishment->telephone !== '-')? $establishment->telephone : '' }}</h6>
            </div>
        </td>
        <td width="30%" class="border-box py-4 px-2 text-center">
            <h5 class="text-center">NOTA DE VENTA</h5>
            <h3 class="text-center">{{ $tittle }}</h3>
        </td>
    </tr>
</table>
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
    </tr>
    @if ($customer->address !== '')
    <tr>
        <td class="align-top">Dirección:</td>
        <td colspan="3">
            {{ $customer->address }}
            {{ ($customer->country_id)? ', '.$customer->country->name : '' }}
            {{ ($customer->department_id)? ', '.$customer->department->name : '' }}
            {{ ($customer->city_id)? '- '.$customer->city->name : '' }}
        </td>
    </tr>
    @endif
    @if ($document->total_canceled)
    <tr>
        <td class="align-top">Estado:</td>
        <td colspan="3">CANCELADO</td>
    </tr>
    @else
    <tr>
        <td class="align-top">Estado:</td>
        <td colspan="3">PENDIENTE DE PAGO</td>
    </tr>
    @endif
</table>

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
        <th class="border-top-bottom text-center py-2" width="15%">UNIDAD</th>
        <th class="border-top-bottom text-left py-2">DESCRIPCIÓN</th>
        <th class="border-top-bottom text-center py-2" width="8%">LOTE</th>
        <th class="border-top-bottom text-center py-2" width="8%">SERIE</th>
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

                @if($row->item->is_set == 1)
                 <br>
                 @inject('itemSet', 'App\Services\ItemSetService')
                    {{join( "-", $itemSet->getItemsSet($row->item_id) )}}
                @endif

            </td>
            <td class="text-center align-top">
                @inject('itemLotGroup', 'App\Services\ItemLotsGroupService')
                @php
                    $lot_code = isset($row->item->lots_group) ? collect($row->item->lots_group)->first(function($row){ return $row->checked == true;}):null;
                @endphp
                {{ 
                    $itemLotGroup->getLote($lot_code ? $lot_code->id : null)
                }}

            </td>
            <td class="text-center align-top">

                @isset($row->item->lots)
                    @foreach($row->item->lots as $lot)

                        <span style="font-size: 9px">{{ $lot->series }}</span><br>

                    @endforeach
                @endisset
            </td>
            <td class="text-right align-top">{{ number_format($row->unit_price, 2) }}</td>
            <td class="text-right align-top">
                {{ $row->discount }}
            </td>
            <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
        </tr>
        <tr>
            <td colspan="8" class="border-bottom"></td>
        </tr>
    @endforeach
    
        <tr>
            <td colspan="7" class="text-right font-bold">TOTAL VENTA: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ $document->sale }}</td>
        </tr>
        <tr >
            <td colspan="7" class="text-right font-bold">TOTAL DESCUENTO (-): {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ $document->total_discount }}</td>
        </tr>

        @foreach ($document->taxes as $tax)
            @if ((($tax->total > 0) && (!$tax->is_retention)))
                <tr >
                    <td colspan="7" class="text-right font-bold">
                        {{$tax->name}}(+): {{ $document->currency->symbol }}
                    </td>
                    <td class="text-right font-bold">{{number_format($tax->total, 2)}} </td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td colspan="7" class="text-right font-bold">SUBTOTAL: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ $document->subtotal }}</td>
        </tr>

        <tr>
            <td colspan="7" class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency->symbol }}</td>
            <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
        </tr>
    </tbody>
</table>
<table class="full-width">
<tr>
    <td>
    <strong>PAGOS:</strong> </td></tr>
        @php
            $payment = 0;
        @endphp
        @foreach($payments as $row)
            <tr><td>- {{ $row->date_of_payment->format('d/m/Y') }} - {{ $row->payment_method_type->description }} - {{ $row->reference ? $row->reference.' - ':'' }} {{ $document->currency->symbol }} {{ $row->payment }}</td></tr>
            @php
                $payment += (float) $row->payment;
            @endphp
        @endforeach
        <tr><td><strong>SALDO:</strong> {{ $document->currency->symbol }} {{ number_format($document->total - $payment, 2) }}</td>
    </tr>

</table>
</body>
</html>
