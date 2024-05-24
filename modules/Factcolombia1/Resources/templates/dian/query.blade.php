<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:con="http://www.dian.gov.co/servicios/facturaelectronica/ConsultaDocumentos">
    <soapenv:Header>
        <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <wsse:UsernameToken wsu:Id="UsernameToken-2">
            <wsse:Username>{{$company->software_identifier}}</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">{{hash('sha256', $company->software_password)}}</wsse:Password>
            </wsse:UsernameToken>
        </wsse:Security>
    </soapenv:Header>
    <soapenv:Body>
        <con:ConsultaResultadoValidacionDocumentosPeticion>
            <con:TipoDocumento>{{$document->type_document_id}}</con:TipoDocumento>
            <con:NumeroDocumento>{{$document->prefix}}{{$document->number}}</con:NumeroDocumento>
            <con:NitEmisor>{{$company->identification_number}}</con:NitEmisor>
            <con:FechaGeneracion>{{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d\TH:i:s')}}</con:FechaGeneracion>
            <con:IdentificadorSoftware>{{$company->software_identifier}}</con:IdentificadorSoftware>
            <con:CUFE>{{$document->cufe}}</con:CUFE>
        </con:ConsultaResultadoValidacionDocumentosPeticion>
    </soapenv:Body>
</soapenv:Envelope>