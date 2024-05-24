@php
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

<table class="full-width">
    <tr>
        @if($filename_logo != "")
            <td width="20%">
                <div class="company_logo_box">
                    <img src="data:{{mime_content_type($filename_logo)}};base64, {{base64_encode(file_get_contents($filename_logo))}}" alt="{{$company->name}}" class="company_logo" style="max-width: 150px;">
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
            <h5 class="text-center">COTIZACIÓN</h5>
            <h3 class="text-center">{{ $title }}</h3>
        </td>
    </tr>
</table>
