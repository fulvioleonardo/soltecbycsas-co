@php
    $establishment = $document->establishment;
    $supplier = $document->supplier;
    //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $tittle = $document->prefix.'-'.str_pad($document->id, 8, '0', STR_PAD_LEFT);

    
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
                {{--<img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">--}}
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
            </div>
        </td>
        <td width="30%" class="border-box py-4 px-2 text-center">
            <h5 class="text-center">ORDEN DE COMPRA</h5>
            <h3 class="text-center">{{ $tittle }}</h3>
        </td>
    </tr>
</table>
<table class="full-width mt-5">
    <tr>
        <td width="15%">Proveedor:</td>
        <td width="45%">{{ $supplier->name }}</td>
        <td width="25%">Fecha de emisión:</td>
        <td width="15%">{{ $document->date_of_issue->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td>{{ $supplier->identity_document_type->name }}:</td>
        <td>{{ $supplier->number }}</td>
        @if($document->date_of_due)
            <td width="25%">Fecha de vencimiento:</td>
            <td width="15%">{{ $document->date_of_due->format('Y-m-d') }}</td>
        @endif
    </tr>
    @if ($supplier->address !== '')
    <tr>
        <td class="align-top">Dirección:</td>
        <td colspan="3">
            {{ $supplier->address }}
            {{ ($supplier->country_id)? ', '.$supplier->country->name : '' }}
            {{ ($supplier->department_id)? ', '.$supplier->department->name : '' }}
            {{ ($supplier->city_id)? '- '.$supplier->city->name : '' }}
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
    @if ($supplier->telephone)
    <tr>
        <td class="align-top">Teléfono:</td>
        <td colspan="3">
            {{ $supplier->telephone }} 
        </td>
    </tr>
    @endif
    @if ($document->payment_method_type)
    <tr>
        <td class="align-top">T. Pago:</td>
        <td colspan="3">
            {{ $document->payment_method_type->description }} 
        </td>
    </tr>
    @endif
    <tr>
        <td class="align-top">Vendedor:</td>
        <td colspan="3">
            {{ $document->user->name }} 
        </td>
    </tr>
    @if($document->sale_opportunity)
    <tr>
        <td class="align-top">O. Venta:</td>
        <td  colspan="3">{{ $document->sale_opportunity->number_full }}</td>
    </tr>
    @endif
</table>

<table class="full-width ">
    @if ($document->purchase_quotation)
        <tr>
            <td width="15%" class="align-top">Proforma: </td>
            <td width="85%">{{ $document->purchase_quotation->identifier }}</td>
        </tr>
    @endif
</table>  
<table class="full-width mt-3">
    @if ($document->description)
        <tr>
            <td width="15%" class="align-top">Descripción: </td>
            <td width="85%">{{ $document->description }}</td>
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
                {{ $row->discount }}
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
</body>
</html>