<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:rep="http://www.dian.gov.co/servicios/facturaelectronica/ReportarFactura">
    <soapenv:Header>
        <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <wsse:UsernameToken wsu:Id="UsernameToken-2">
                <wsse:Username>{{$company->software_identifier}}</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">{{hash('sha256', $company->software_password)}}</wsse:Password>
                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">FmbZRkx1jh2A+imgjD2fLQ==</wsse:Nonce>
                <wsu:Created>{{Carbon\Carbon::now()->format('Y-m-d\TH:i:s.v\Z')}}</wsu:Created>{{-- date('Y-m-d\TH:i:s.000\Z') --}}
            </wsse:UsernameToken>
        </wsse:Security>
    </soapenv:Header>
    <soapenv:Body>
        <rep:EnvioFacturaElectronicaPeticion>
            <rep:NIT>{{$company->identification_number}}</rep:NIT>
            <rep:InvoiceNumber>{{$document->prefix}}{{$document->number}}</rep:InvoiceNumber>
            <rep:IssueDate>{{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d\TH:i:s')}}</rep:IssueDate>
            <rep:Document>{{$zipBase64}}</rep:Document>
        </rep:EnvioFacturaElectronicaPeticion>
    </soapenv:Body>
</soapenv:Envelope>