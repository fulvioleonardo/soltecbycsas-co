<?xml version="1.0" encoding="UTF-8"?>
<fe:CreditNote
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
    xsi:schemaLocation="http://www.dian.gov.co/contratos/facturaelectronica/v1 http://www.dian.gov.co/micrositios/fac_electronica/documentos/XSD/r0/DIAN_UBL.xsd urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2 http://www.dian.gov.co/micrositios/fac_electronica/documentos/common/UnqualifiedDataTypeSchemaModule-2.0.xsd urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2 http://www.dian.gov.co/micrositios/fac_electronica/documentos/common/UBL-QualifiedDatatypes-2.0.xsd">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent>
                <sts:DianExtensions>
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
    <cbc:CustomizationID schemeDataURI="http://www.dian.gov.co/face/xsdList.html" schemeURI="http://www.dian.gov.co/face/xsd/DIAN_UBL.xsd">DIAN v1.0</cbc:CustomizationID>
    <cbc:ProfileID schemeDataURI="http://www.dian.gov.co/face/profileList.html">Nota Crédito, todos los perfiles</cbc:ProfileID>
    <cbc:ID schemeName="identificador del CreditNote asignado por el vendedor">{{$document->prefix}}{{$document->number}}</cbc:ID>
    <cbc:UUID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeName="UUID de la entrega" schemeURI="http://www.dian.gov.co/contratos/facturaelectronica/v1/anexo_v1_0.pdf#uuidEntrega">3ae8314161ad287caaff44cd29efebaf8da3dea4</cbc:UUID>
    <cbc:IssueDate>{{Carbon\Carbon::parse($document->date_issue)->format('Y-m-d')}}</cbc:IssueDate>
    <cbc:IssueTime>{{Carbon\Carbon::parse($document->date_issue)->format('H:i:s')}}</cbc:IssueTime>
    <cbc:Note/>
    <cbc:DocumentCurrencyCode>{{$company->currency->code}}</cbc:DocumentCurrencyCode>
    <cac:DiscrepancyResponse>
        <cbc:ReferenceID schemeName="UUID del ApplicationResponse del comprador"/>
        <cbc:ResponseCode listName="Cod. del vendedor para procesar ApplicationResponse">{{$document->note_concept_id}}</cbc:ResponseCode>
    </cac:DiscrepancyResponse>
    <cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID schemeName="número de la factura a anular || abonar">{{$document->reference->prefix}}{{$document->reference->number}}</cbc:ID>
            <cbc:UUID schemeName="UUID ó CUFE a anular || abonar">{{$document->reference->cufe}}</cbc:UUID>
            <cbc:IssueDate>{{Carbon\Carbon::parse($document->reference->date_issue)->format('Y-m-d')}}</cbc:IssueDate>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
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
                <cbc:ID schemeAgencyID="195" schemeAgencyName="CO, DIAN (Direccion de Impuestos y Aduanas Nacionales)" schemeID="13">{{$document->client->identification_number}}</cbc:ID>
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
            <fe:PartyLegalEntity><cbc:RegistrationName/></fe:PartyLegalEntity>
            <fe:Person>
                <cbc:FirstName languageID="EN-US">{{explode(' ', $document->client->name, 2)[0]}}</cbc:FirstName>
                <cbc:FamilyName languageID="EN-US">{{(explode(' ', $document->client->name, 2)[1]) ?? null}}</cbc:FamilyName>
                <cbc:MiddleName languageID="EN-US"/>
            </fe:Person>
        </fe:Party>
    </fe:AccountingCustomerParty>
    <fe:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="{{$document->currency->code}}">{{number_format($document->sale, 2, '.', '')}}</cbc:LineExtensionAmount>
        <cbc:TaxExclusiveAmount currencyID="{{$document->currency->code}}">{{number_format(($document->sale - $document->total_discount), 2, '.', '')}}</cbc:TaxExclusiveAmount>
        <cbc:PayableAmount currencyID="{{$document->currency->code}}">{{number_format($document->total, 2, '.', '')}}</cbc:PayableAmount>
    </fe:LegalMonetaryTotal>
    @foreach ($document->detail_documents()->get() as $key => $detail)
        <cac:CreditNoteLine>
            <cbc:ID schemeName="identificador para el CreditNoteLine">{{$index++}}</cbc:ID>
            <cbc:UUID schemeName="UUID para el CreditNoteLine"/>
            <cbc:Note/>
            <cbc:LineExtensionAmount currencyID="{{$document->currency->code}}">{{$detail->price}}</cbc:LineExtensionAmount>
            <cbc:AccountingCost>No tiene</cbc:AccountingCost>
            <cac:Item>
                <cbc:Description>{{$detail->item->name}}</cbc:Description>
            </cac:Item>
        </cac:CreditNoteLine>
    @endforeach
</fe:CreditNote>