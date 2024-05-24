<?xml version="1.0" encoding="UTF-8"?>
<fe:Invoice
    xmlns:fe="http://www.dian.gov.co/contratos/facturaelectronica/v1"
    xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
    xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
    xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001"
    xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001"
    xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003"
    xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
    xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
    xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures"
    xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.dian.gov.co/contratos/facturaelectronica/v1 ../xsd/DIAN_UBL.xsd urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2 ../../ubl2/common/UnqualifiedDataTypeSchemaModule-2.0.xsd urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2 ../../ubl2/common/UBL-QualifiedDatatypes-2.0.xsd">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sts:DianExtensions>
                    <sts:InvoiceControl>
                        <sts:InvoiceAuthorization>{{$document->type_document->resolution_number}}</sts:InvoiceAuthorization>
                        <sts:AuthorizationPeriod>
                            <cbc:StartDate>{{$document->type_document->resolution_date}}</cbc:StartDate>
                            <cbc:EndDate>{{Carbon\Carbon::parse($document->type_document->resolution_date)->addYears(2)->format('Y-m-d')}}</cbc:EndDate>
                        </sts:AuthorizationPeriod>
                        <sts:AuthorizedInvoices>
                            <sts:Prefix>{{$document->type_document->prefix}}</sts:Prefix>
                            <sts:From>{{$document->type_document->from}}</sts:From>
                            <sts:To>{{$document->type_document->to}}</sts:To>
                        </sts:AuthorizedInvoices>
                    </sts:InvoiceControl>
                    <sts:InvoiceSource>
                        <cbc:IdentificationCode listAgencyID="6" listAgencyName="United Nations Economic Commission for Europe" listSchemeURI="urn:oasis:names:specification:ubl:codelist:gc:CountryIdentificationCode-2.0">{{$company->country->code}}</cbc:IdentificationCode>
                    </sts:InvoiceSource>
                    <sts:SoftwareProvider>
                        <sts:ProviderID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)">{{$company->identification_number}}</sts:ProviderID>
                        <sts:SoftwareID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)">{{$company->software_identifier}}</sts:SoftwareID>
                    </sts:SoftwareProvider>
                    <sts:SoftwareSecurityCode schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)">{{hash('sha384', "{$company->software_identifier}{$company->pin}")}}</sts:SoftwareSecurityCode>
                </sts:DianExtensions>
            </ext:ExtensionContent>
        </ext:UBLExtension>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>{{$company->version_ubl->name}}</cbc:UBLVersionID>
    <cbc:ProfileID schemeName="Profile" schemeURI="http://www.dian.gov.co/contratos/facturaelectronica/v1/anexo_v1_0.pdf#facExportProfile">DIAN 1.0</cbc:ProfileID>
    <cbc:ID>{{$document->prefix}}{{$document->number}}</cbc:ID>
    <cbc:UUID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeName="CUFE" schemeURI="http://www.dian.gov.co/contratos/facturaelectronica/v1/anexo_v1_0.pdf#CUFE">{{$document->cufe}}</cbc:UUID>
    <cbc:IssueDate>{{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d')}}</cbc:IssueDate>
    <cbc:IssueTime>{{Carbon\Carbon::parse($document->date_issue)->format('H:i:s')}}</cbc:IssueTime>
    <cbc:InvoiceTypeCode listAgencyID="195" listAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" listSchemeURI="http://www.dian.gov.co/contratos/facturaelectronica/v1/InvoiceType" listURI="http://www.dian.gov.co">{{$document->type_document_id}}</cbc:InvoiceTypeCode>
    <cbc:Note/>
    <cbc:DocumentCurrencyCode>{{$document->currency->code}}</cbc:DocumentCurrencyCode>
    <fe:AccountingSupplierParty>
        <cbc:AdditionalAccountID>1</cbc:AdditionalAccountID>
        <fe:Party>
            <cbc:WebsiteURI/>
            <cac:PartyIdentification>
                <cbc:ID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeID="{{$company->type_identity_document->code}}" schemeURI="http://www.dian.gov.co/contratos/facturaelectronica/v1/anexo_v1_0.pdf#nit">{{$company->identification_number}}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name>{{$company->name}}</cbc:Name>
            </cac:PartyName>
            <fe:PhysicalLocation>
                <fe:Address>
                    <cbc:Department>{{$company->department->name}}</cbc:Department>
                    <cbc:CityName>{{$company->city->name}}</cbc:CityName>
                    <cac:AddressLine>
                        <cbc:Line>{{$company->address}}</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{$company->country->code}}</cbc:IdentificationCode>
                    </cac:Country>
                </fe:Address>
            </fe:PhysicalLocation>
            <fe:PartyTaxScheme>
                <cbc:TaxLevelCode>{{$company->type_regime_id}}</cbc:TaxLevelCode>
                <cac:TaxScheme/>
            </fe:PartyTaxScheme>
            <fe:PartyLegalEntity>
                <cbc:RegistrationName>{{$company->name}}</cbc:RegistrationName>
            </fe:PartyLegalEntity>
            <fe:Person>
                <cbc:FirstName languageID="EN-US"/>
                <cbc:FamilyName languageID="EN-US"/>
                <cbc:MiddleName languageID="EN-US"/>
            </fe:Person>
        </fe:Party>
    </fe:AccountingSupplierParty>
    <fe:AccountingCustomerParty>
        <cbc:AdditionalAccountID>2</cbc:AdditionalAccountID>
        <fe:Party>
            <cbc:WebsiteURI/>
            <cac:PartyIdentification>
                <cbc:ID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeID="{{$document->client->type_identity_document->code}}">{{$document->client->identification_number}}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name>{{$document->client->name}}</cbc:Name>
            </cac:PartyName>
            <fe:PhysicalLocation>
                <fe:Address>
                    <cbc:Department>{{$document->departament_client->name ?? null}}</cbc:Department>
                    <cbc:CityName>{{$document->city_client->name ?? null}}</cbc:CityName>
                    <cac:AddressLine>
                        <cbc:Line>{{$document->client->address}}</cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{$document->country_client->code ?? null}}</cbc:IdentificationCode>
                    </cac:Country>
                </fe:Address>
            </fe:PhysicalLocation>
            <fe:PartyTaxScheme>
                <cbc:TaxLevelCode>{{$document->client->type_regime_id}}</cbc:TaxLevelCode>
                <cac:TaxScheme/>
            </fe:PartyTaxScheme>
            <fe:PartyLegalEntity>
                <cbc:RegistrationName/>
            </fe:PartyLegalEntity>
            <fe:Person>
                <cbc:FirstName languageID="EN-US">{{explode(' ', $document->client->name, 2)[0]}}</cbc:FirstName>
                <cbc:FamilyName languageID="EN-US">{{(explode(' ', $document->client->name, 2)[1]) ?? null}}</cbc:FamilyName>
                <cbc:MiddleName languageID="EN-US"/>
            </fe:Person>
        </fe:Party>
    </fe:AccountingCustomerParty>
    @foreach ($taxes as $tax)
        <fe:TaxTotal>
            <cbc:TaxAmount currencyID="{{$document->currency->code}}">{{number_format(($document->taxes_collect->where('id', $tax->id)->first()->is_retention) ? $document->taxes_collect->where('id', $tax->id)->first()->retention : $document->taxes_collect->where('id', $tax->id)->first()->total, 2, '.', '')}}</cbc:TaxAmount>
            <cbc:TaxEvidenceIndicator>{{$tax->is_retention ? 'true' : 'false'}}</cbc:TaxEvidenceIndicator>
            <fe:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{$document->currency->code}}">{{number_format(($document->taxes_collect->where('id', $tax->id)->first()->is_retention) ? $document->taxes_collect->where('id', $tax->id)->first()->retention : $document->taxes_collect->where('id', $tax->id)->first()->total, 2, '.', '')}}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{$document->currency->code}}">{{number_format(($document->taxes_collect->where('id', $tax->id)->first()->is_retention) ? $document->taxes_collect->where('id', $tax->id)->first()->retention : $document->taxes_collect->where('id', $tax->id)->first()->total, 2, '.', '')}}</cbc:TaxAmount>
                <cbc:Percent>{{$tax->rate}}</cbc:Percent>
                <cac:TaxCategory>
                    <cac:TaxScheme>
                        <cbc:ID>{{$tax->code}}</cbc:ID>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </fe:TaxSubtotal>
        </fe:TaxTotal>
    @endforeach
    <fe:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="{{$document->currency->code}}">{{number_format($document->sale, 2, '.', '')}}</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="{{$document->currency->code}}">{{number_format(($document->sale - $document->total_discount), 2, '.', '')}}</cbc:TaxExclusiveAmount>
        <cbc:PayableAmount currencyID="{{$document->currency->code}}">{{number_format($document->total, 2, '.', '')}}</cbc:PayableAmount>
    </fe:LegalMonetaryTotal>
    @foreach ($document->detail_documents()->get() as $key => $detail)
        <fe:InvoiceLine>
            <cbc:ID>{{$index++}}</cbc:ID>
            <cbc:Note></cbc:Note>
            <cbc:InvoicedQuantity>{{$detail->quantity}}</cbc:InvoicedQuantity>
            <cbc:LineExtensionAmount currencyID="{{$document->currency->code}}">{{$detail->price}}</cbc:LineExtensionAmount>
            <fe:Item>
                <cbc:Description>{{$detail->item->name}}</cbc:Description>
                <cbc:AdditionalInformation>01</cbc:AdditionalInformation>
            </fe:Item>
            <fe:Price>
                <cbc:PriceAmount currencyID="{{$document->currency->code}}">{{$detail->price}}</cbc:PriceAmount>
            </fe:Price>
        </fe:InvoiceLine>
    @endforeach
</fe:Invoice>