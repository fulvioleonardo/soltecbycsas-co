<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<title>COTIZACION Nro: COT-{{$document->id}}</title>
</head>

<body>
    <style type="text/css">
 * {
    margin: 0; padding: 0;
    font-family: 'sans-serif'
  }

html, body{
    vertical-align: baseline
}

article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section{
    display: block
}

body{
    line-height: 1
}

ol, ul{
    list-style: none
}

blockquote, q{
    quotes: none
}

blockquote:before, blockquote:after, q:before, q:after{
    content: '';
    content: none
}

table{
    border-collapse: collapse;
    border-spacing: 0
}

body{
    font-family: 'sans-serif';
    font-size: 14px
}

strong{
    font-weight: 700
}

.row{
    font-size: 11px;
}

#container{
    position: relative;
    padding: 4%;
    width: 750px;
    margin: 0 auto;
}

#header{
    height: 80px
}

#header > #reference{
    position: absolute;
    margin-top: 20px;
}

#reference{
    font-size: 10px;
}

#header > #reference h3{
    margin: 0
}

#header > #reference h4{
    margin: 0;
    font-size: 85%;
    font-weight: 600
}

#header > #reference p{
    margin: 0;
    margin-top: 2%;
    font-size: 11px;
    text-align: center;
}

#header > #logo{
    width: 100%;
    float: left
}

#fromto{
    height: 160px
}

#fromto > #from, #fromto > #to{
    width: 33%;
    min-height: 90px;
    margin-top: 30px;
    font-size: 85%;
    padding: 1.5%;
    line-height: 120%
}

#fromto > #from{
    float: left;
    width: 33%;
    background:#efefef;
    margin-top: 30px;
    font-size: 85%;
    padding: 1.5%
}

#fromto > #to{
    float: left;
    margin-left: 12px;
    width: 31%;
    border: solid grey 1px
}

.subheader{
    margin-top: 10px
}

.subheader > p{
    font-weight: 700;
    text-align: right;
    margin-bottom: 1%;
    font-size: 65%
}

.subheader > table{
    width: 100%;
    font-size: 85%;
    border: solid grey 1px
}

.subheader > table th:first-child{
    text-align: left
}

.subheader > table th{
    font-weight: 400;
    padding: 1px 4px
}

.subheader > table td{
    padding: 1px 4px
}

.subheader > table th:nth-child(2), .subheader > table th:nth-child(4){
    width: 45px
}

.subheader > table th:nth-child(3){
    width: 60px
}

.subheader > table th:nth-child(5){
    width: 80px
}

.subheader > table tr td:not(:first-child){
    text-align: right;
    padding-right: 1%
}

.subheader table td{
    border-right:solid grey 1px
}

.subheader table tr td{
    padding-top: 3px;
    padding-bottom: 3px;
    height: 10px
}

.subheader table tr:nth-child(1){
    border: solid grey 1px
}

.subheader table tr th{
    border-right: solid grey 1px;
    padding: 3px
}

.subheader table tr:nth-child(2) > td{
    padding-top: 8px
}

.items{
    margin-top: 10px
}

.items > p{
    font-weight: 700;
    text-align: right;
    margin-bottom: 1%;
    font-size: 65%
}

.items > table{
    width: 100%;
    font-size: 85%;
    border: solid grey 1px
}

.items > table th:first-child{
    text-align: left
}

.items > table th{
    font-weight: 400;
    padding: 1px 4px
}

.items > table td{
    padding: 1px 4px
}

.items > table th:nth-child(2), .items > table th:nth-child(4){
    width: 45px
}

.items > table th:nth-child(3){
    width: 60px
}
.items > table th:nth-child(5){
    width: 80px
}

.items > table tr td:not(:first-child){
    text-align: right;
    padding-right: 1%
}

.items table td{
    border-right: solid grey 1px
}

.items table tr td{
    padding-top: 3px;
    padding-bottom: 3px;
    height: 10px
}

.items table tr:nth-child(1){
    border: solid grey 1px
}

.items table tr th{
    border-right: solid grey 1px;
    padding: 3px
}

.items table tr:nth-child(2) > td{
    padding-top: 8px
}

.summary{
    height: 130px;
    margin-top: 30px
}

.summary #note{
    float: left
}

.summary #note h4{
    font-size: 10px;
    font-weight: 600;
    font-style: italic;
    margin-bottom: 4px
}

.summary #note p{
    font-size: 10px;
    font-style: italic
}

.summary #total table{
    font-size: 85%;
    width: 260px;
    float: right;
    margin-top: 40px;
}

.summary #total table td{
    padding: 3px 4px
}

.summary #total table tr td:last-child{
    text-align: right
}

.summary #total table tr:nth-child(3){
    background:#efefef;
    font-weight: 600
}

#footer{
    margin: auto;
    margin-top: 20px;
    left: 4%;
    bottom: 4%;
    right: 4%;
    border-top: solid grey 1px
}

#footer p{
    margin-top: 1%;
    font-size: 65%;
    line-height: 140%;
    text-align: center
}

.sinbode tr td, .sinbode tr, .sinbode tr th{
    border: none!important;
}

.summarys{
    border: solid grey 1px;
    margin-top: 10px;
    padding: 10px;
}

.summaryss{
    border: solid grey 1px;
    margin-top: 10px;
    padding: 3px;
}

#fromto > #from, #fromto > #qr {
    width: 11%;
    min-height: 90px;
    margin-top: 30px;
    font-size: 85%;
    padding: 1.5%;
    line-height: 120%;
}

#fromto > #from {
    width: 46%;
    min-height: 90px;
    margin-top: 30px;
    font-size: 85%;
    padding: 1.5%;
    line-height: 120%;
}

#fromto > #qr {
    float: right;
}

.summary #firma table td, .summary #firma table tr{
    border: none !important;
}

#empresa-header{
    text-align: center;
    font-size: 14px;
}

#empresa-header1{
    text-align: center;
    font-size: 10px;
}
    </style>
    <div id="container">
        <div id="header">
            <div class="row">
                <div class="col-sm-5">
                    <div id="reference" style="text-align: center;">
                        <p style="text-align: center;"><strong>COTIZACION NRO</strong></p>
                        <p style="color: red;
                            font-weight: bold;
                            font-size: 14px;
                            text-align: center;
                            margin-top: 3px;
                            margin-bottom: 3px;
                            border: 1px solid #000;
                            padding: 5px;
                            line-height: 10px;
                            border-radius: 6px;">COT-{{$document->id}}</p>
                        <p>Fecha: {{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d')}}<br>
                           Hora: {{Carbon\Carbon::parse($document->date_issue)->format('H:m:s')}}</p>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div id="empresa-header">
                        <strong>{{$company->name}}</strong><br>
                    </div>
                    <div id="empresa-header1">
                        NIT: {{$servicecompany->identification_number}} - {{$servicecompany->type_regime->name}} - {{$company->type_regime->name}}<br>
                        {{$company->type_identity_document->name}}<br>
                        TARIFA ICA: {{$company->ica_rate}}% - ACTIVIDAD ECONOMICA: {{$company->economic_activity_code}}<br>
                        REPRESENTACION GRAFICA DE COTIZACION DE PRODUCTOS Y SERVICIOS<br>
                        {{$servicecompany->address}} - {{$servicecompany->municipality->name}} - {{$servicecompany->country->name}} Telefono - {{$servicecompany->phone}}<br>
                        E-mail: {{$company->email}}<br>
                    </div>
                </div>
            </div>    
        </div>
        <br/><br/>

        <div class="row">
            <div class="col-sm-1">
                <p>
                    <strong>CC o NIT:</strong><br>
                            Cliente:<br>
                            Regimen:<br>
                            Dirección:<br>
                            Ciudad:<br>
                            Telefono:<br>
                            E-mail:<br>
                </p>    
            </div>    

            <div class="col-sm-3">
                <p>
                    <strong>{{$document->client->identification_number}}-{{$document->client->dv ?? NULL}}</strong><br>
                            {{$document->client->name}}<br>
                            @inject('regimen', 'App\Models\Tenant\TypeRegime')
                            {{$regimen->findOrFail($document->client->type_regime_id)->name}}<br>
                            {{$document->client->address}}<br>
                            @inject('cliente', 'App\Models\Tenant\Client')
                            @inject('ciudad', 'App\Models\Tenant\City')
                            @inject('pais', 'App\Models\Tenant\Country')
                            {{$ciudad->findOrFail($cliente->findOrFail($document->client->id)->city_id)->name}} - {{$pais->findOrFail($cliente->findOrFail($document->client->id)->country_id)->name}}<br>
                            {{$document->client->phone}}<br>
                            {{$document->client->email}}<br>
                </p>
            </div>
        </div>
        <br/><br/>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed table-striped table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Descripcion</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">UM</th>
                            <th class="text-center">Val. Unit</th>
                            <th class="text-center">IVA/IC</th>
                            <th class="text-center">Dcto</th>
                            <th class="text-center">Val. Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($document->detail_quotations as $item)
                            <tr>
                                <td>{{$item->item->code}}</td>
                                <td>{{$item->item->name}}</td>
                                <td class="text-right">{{number_format($item->quantity, 2)}}</td>
                                <td class="text-right">{{$item->item->type_unit->name}}</td>
                                <td class="text-right">{{number_format($item->price, 2)}}</td>
                                <td class="text-right">{{number_format($item->total_tax, 2)}}</td>
                                <td class="text-right">{{number_format($item->discount, 2)}}</td>
                                <td class="text-right">{{number_format($item->total, 2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-condensed table-striped table-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Impuestos</th>
                            <th class="text-center">Retenciones</th>
                            <th class="text-center">Totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <table class="table table-bordered table-condensed table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Base</th>
                                            <th class="text-center">Porcentaje</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($document->taxes))
                                            <?php $TotalImpuestos = 0; ?>
                                            @foreach($document->taxes as $item)
                                                @if ($item->total > 0)
                                                    <?php $TotalImpuestos = $TotalImpuestos + $item->total ?>
                                                    <tr>
                                                        <td>{{$item->type_tax->name}}</td>
                                                        <td>{{number_format($item->total/($item->rate/100), 2)}}</td>
                                                        <td>{{$item->rate}}%</td>
                                                        <td>{{number_format($item->total, 2)}}</td>
                                                    </tr>
                                                @endif    
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table class="table table-bordered table-condensed table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Tipo</th>
                                            <th class="text-center">Base</th>
                                            <th class="text-center">Porcentaje</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($document->taxes))
                                            <?php $TotalRetenciones = 0; ?>
                                            @foreach($document->taxes as $item)
                                                @if ($item->apply)
                                                    <?php $TotalRetenciones = $TotalRetenciones + $item->retention ?>
                                                    <tr>
                                                        <td>{{$item->type_tax->name}}</td>
                                                        <td>{{number_format($item->retention/($item->rate/100), 2)}}</td>
                                                        <td>{{$item->rate}}%</td>
                                                        <td>{{number_format($item->retention, 2)}}</td>
                                                    </tr>
                                                @endif    
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table class="table table-bordered table-condensed table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Base:</td>
                                            <td>{{number_format($document->sale, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Impuestos:</td>
                                            <td>{{number_format($TotalImpuestos, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Retenciones:</td>
                                            <td>{{number_format($TotalRetenciones, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Dctos (-):</td>
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
            </div>
        </div>

        <div class="summarys">
            <div id="note">
                @inject('Varios', 'App\Custom\Various')
                <h4>NOTAS:</h4>
                <br>{{$document->observation}}
                <p><br>
                <strong>SON</strong>: {{$Varios->NumerosALetras($document->total)}} M/CTE*********.</p>
            </div>
        </div>

        <div class="summary" >
            <div id="note">
                <p>CUALQUIER INQUIETUD ACERCA DE ESTE DOCUMENTO, COMUNICARSE AL TELEFONO {{$document->client->phone}} o al e-mail {{$document->client->email}}<br>
                    <br>
                    <div id="firma">
                        <p><strong>FIRMA ACEPTACIÓN:</strong></p><br>
                        <p><strong>CC:</strong></p><br>
                        <p><strong>FECHA:</strong></p><br>
                    </div>
                </p>
            </div>
        </div>
        <br/>

        <div id="footer">
            <p id='mi-texto'>Cotizacion No: {{$document->id}} - Fecha: {{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d')}}<br></p>
        </div>
    </div>   
</body>
</html>
