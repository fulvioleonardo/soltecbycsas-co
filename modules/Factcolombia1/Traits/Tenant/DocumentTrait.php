<?php

namespace Modules\Factcolombia1\Traits\Tenant;

use Modules\Factcolombia1\Mail\Tenant\SendGraphicRepresentation;
use Illuminate\Support\Facades\Mail;
use Modules\Factcolombia1\Models\Tenant\{
    LogDocument,
    Document,
    Company,
    Client,
    Tax
};
use Carbon\Carbon;
use DomDocument,
    ZipArchive,
    Storage;

use Illuminate\Support\Facades\Log;
use Exception;

/**
 *
 */
trait DocumentTrait
{
    /**
     * Document
     * @var \Modules\Factcolombia1\Models\Tenant\Document
     */
    public $document;

    /**
     * Company
     * @var \Modules\Factcolombia1\Models\Tenant\Company
     */
    public $company;

    /**
     * Tax code cufe
     * @var array
     */
    public $taxCodeCufe = ['01', '02', '03'];

    /**
     * Sign XMLNS
     * @var array
     */
    public $signXMLNS = [
        'xmlns:cac' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        'xmlns:cbc' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        'xmlns:clm54217' => 'urn:un:unece:uncefact:codelist:specification:54217:2001',
        'xmlns:clm66411' => 'urn:un:unece:uncefact:codelist:specification:66411:2001',
        'xmlns:clmIANAMIMEMediaType' => 'urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003',
        'xmlns:ds' => 'http://www.w3.org/2000/09/xmldsig#',
        'xmlns:ext' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2',
        'xmlns:fe' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1',
        'xmlns:qdt' => 'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2',
        'xmlns:sts' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures',
        'xmlns:udt' => 'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2',
        'xmlns:xades' => 'http://uri.etsi.org/01903/v1.3.2#" xmlns:xades141="http://uri.etsi.org/01903/v1.4.1#',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
    ];

    /**
     * Key XMLNS
     * @var array
     */
    public $keyXMLNS = [
        'xmlns:cac' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        'xmlns:cbc' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        'xmlns:clm54217' => 'urn:un:unece:uncefact:codelist:specification:54217:2001',
        'xmlns:clm66411' => 'urn:un:unece:uncefact:codelist:specification:66411:2001',
        'xmlns:clmIANAMIMEMediaType' => 'urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003',
        'xmlns:ds' => 'http://www.w3.org/2000/09/xmldsig#',
        'xmlns:ext' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2',
        'xmlns:fe' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1',
        'xmlns:qdt' => 'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2',
        'xmlns:sts' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures',
        'xmlns:udt' => 'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
    ];

    /**
     * Sign info XMLNS
     * @var [type]
     */
    public $signInfoXMLNS = [
        'xmlns:cac' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        'xmlns:cbc' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        'xmlns:clm54217' => 'urn:un:unece:uncefact:codelist:specification:54217:2001',
        'xmlns:clm66411' => 'urn:un:unece:uncefact:codelist:specification:66411:2001',
        'xmlns:clmIANAMIMEMediaType' => 'urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003',
        'xmlns:ds' => 'http://www.w3.org/2000/09/xmldsig#',
        'xmlns:ext' => 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2',
        'xmlns:fe' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1',
        'xmlns:qdt' => 'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2',
        'xmlns:sts' => 'http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures',
        'xmlns:udt' => 'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2',
        'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
    ];

    /**
     * Response codes
     * @var array
     */
    public $responseCodes = [
        '100' => 'Error al procesar la solicitud WS entrante',
        '101' => 'El formato de los datos del ejemplar recibido no es correcto: Archivo Zip está vacío',
        '102' => 'El formato de los datos del ejemplar recibido no es correcto: Las entradas de directorio no están permitidos',
        '103' => 'El formato de los datos del ejemplar recibido no es correcto: Tamaño de archivo comprimido zip es 0 o desconocido',
        '104' => 'El formato de los datos del ejemplar recibido no es correcto: Sólo un archivo es permitido por archivo Zip',
        '200' => 'Ejemplar recibido exitosamente pasará a verificación',
        '300' => 'Archivo no soportado: Solo reconoce los tipos Invoice, DebitNote o CreditNote',
        '310' => 'El ejemplar contiene errores de validación semantica',
        '320' => 'Parámetros de solicitud de servicio web, no coincide contra el archivo',
        '500' => 'Internal service error: tray again later'
    ];

    /**
     * Description transaction query
     * @var array
     */
    public $descriptionTransactionQuery = [
        '200' => 'Transacción Exitosa',
        '300' => 'Excepción en el Sistema',
        '310' => 'Parámetros enviados con error',
        '320' => 'No existe información'
    ];

    /**
     * Response codes query
     * @var array
     */
    public $responseCodesQuery = [
        '7200001' => 'Recibio',
        '7200002' => 'Exitoso',
        '7200003' => 'En proceso de validación',
        '7200004' => 'Fallido (Documento no cumple 1 o más validaciones de DIAN)',
        '7200005' => 'Error (El xml no es válido)'
    ];

    /**
     * Get cufe
     * @return void
     */
    public function getCufe() {
        $cufe = "{$this->document->prefix}{$this->document->number}";
        $cufe .= Carbon::parse($this->document->date_issue)->format('YmdHis');
        $cufe .= number_format($this->document->sale, 2, '.', '');

        foreach ($this->taxes = Tax::query()->whereNotNull('code')->where('code', '!=', '')->get() as $tax) {
            if (in_array($tax->code, $this->taxCodeCufe)) {
                $cufe .= $tax->code.number_format(($this->document->taxes_collect->where('id', $tax->id)->first()->is_retention) ? $this->document->taxes_collect->where('id', $tax->id)->first()->retention : $this->document->taxes_collect->where('id', $tax->id)->first()->total, 2, '.', '');
            }
        }

        $cufe .= number_format($this->document->total, 2, '.', '');
        $cufe .= $this->company->identification_number;
        // $cufe .= $this->document->client->type_identity_document->code;
        // $cufe .= $this->document->client->identification_number;
        $cufe .= $this->document->customer->identity_document_type->code;
        $cufe .= $this->document->customer->number;
        $cufe .= $this->document->type_document->technical_key;

        return sha1($cufe);
    }

    /**
     * Get file name
     * @param  string $type
     * @return string
     */
    public function getFileName($type = 'xml') {
        return "{$this->document->type_document->template}".str_pad($this->company->identification_number, 10, 0, STR_PAD_LEFT).str_pad(dechex($this->document->number), 10, 0, STR_PAD_LEFT).".{$type}";
    }

    /**
     * Get path file
     * @param  string $type
     * @return string
     */
    public function getPathFile($type = 'xml') {
        return "{$this->document->type_document->template}/{$this->getFileName($type)}";
    }

    /**
     * Template
     * @return string
     */
    public function template() {
        return str_replace(['.', ' '], '', $this->company->version_ubl->name).".{$this->document->type_document->template}";
    }

    /**
     * Save file document
     * @return void
     */
    public function saveFileDocument() {
        Storage::disk('tenant')->put($this->getPathFile(), view($this->template(), [
                'document' => $this->document,
                'company' => $this->company,
                'taxes' => $this->taxes,
                'index' => 1
            ])
            ->render());
    }

    /**
     * Save file and sign document
     * @return void
     */
    public function saveFileAndSignDocument() {
        if (openssl_pkcs12_read(Storage::disk('tenant')->get("certificates/{$this->company->certificate_name}"), $infoCert, $this->company->certificate_password)) {
            throw_if((empty($infoCert['cert']) || empty($infoCert['pkey'])), \Exception::class, 'Failed to get public and private certificate key.');

            $xml = view($this->template(), [
                'document' => $this->document,
                'company' => $this->company,
                'taxes' => $this->taxes,
                'index' => 1
            ])
            ->render();

            $sign = str_replace(["\r", "\n"], '', view('sign.signed_properties', ['infoCert' => $infoCert])->render());

            $signXMLNS = implode(' ', array_map(function($value, $key) {
                return "{$key}=\"$value\"";
            }, $this->signXMLNS,  array_keys($this->signXMLNS)));

            $signString = $this->retC14DigestSha1(str_replace('<xades:SignedProperties', "<xades:SignedProperties {$signXMLNS}", $sign));

            openssl_x509_export($infoCert["cert"], $stringCert);
            $stringCert =str_replace(["\r", "\n", "-----BEGIN CERTIFICATE-----", "-----END CERTIFICATE-----"], '', $stringCert);

            $key = str_replace(["\r", "\n"], '', view('sign.key_info', ['stringCert' => $stringCert])->render());

            $keyXMLNS = implode(' ', array_map(function($value, $key) {
                return "{$key}=\"$value\"";
            }, $this->keyXMLNS,  array_keys($this->keyXMLNS)));

            $keyString = $this->retC14DigestSha1(str_replace('<ds:KeyInfo', "<ds:KeyInfo {$keyXMLNS}", $key));

            $xmlString = new DOMDocument('1.0');
            $xmlString->loadXML(str_replace(["\r", "\n"], '', $xml));
            $canonized = $xmlString->C14N();
            $canonizadorealBase64 = base64_encode(sha1($canonized, true));

            $signInfo = str_replace(["\r", "\n"], '', view('sign.signed_info', ['canonizadorealBase64' => $canonizadorealBase64, 'keyString' => $keyString, 'signString' => $signString])->render());

            $signInfoXMLNS = implode(' ', array_map(function($value, $key) {
                return "{$key}=\"$value\"";
            }, $this->signInfoXMLNS,  array_keys($this->signInfoXMLNS)));

            $signInfoString = str_replace('<ds:SignedInfo', "<ds:SignedInfo {$signInfoXMLNS}", $signInfo);

            $DOMDocument = new DOMDocument('1.0','UTF-8');
            $DOMDocument->loadXML($signInfoString);

            openssl_sign($DOMDocument->C14N(), $resultSignature, $infoCert["pkey"]);
            $resultSignature = base64_encode($resultSignature);

            $signatureString = "<ds:Signature xmlns:ds=\"http://www.w3.org/2000/09/xmldsig#\" Id=\"xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a\">{$signInfo}<ds:SignatureValue Id=\"xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a-sigvalue\">{$resultSignature}</ds:SignatureValue>{$key}<ds:Object><xades:QualifyingProperties xmlns:xades=\"http://uri.etsi.org/01903/v1.3.2#\" xmlns:xades141=\"http://uri.etsi.org/01903/v1.4.1#\" Target=\"#xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a\">{$sign}</xades:QualifyingProperties></ds:Object></ds:Signature>";

            $searchTag = '<ext:ExtensionContent/>';

            if (!($pos = strrpos(str_replace(["\r", "\n"], '', $xml), $searchTag)) !== false) throw new \Exception('Error inserting the signature in the document', 1);

            Storage::disk('tenant')->put($this->getPathFile(), substr_replace(str_replace(["\r", "\n"], '', $xml), "<ext:ExtensionContent>{$signatureString}</ext:ExtensionContent>", $pos, strlen($searchTag)));
        }
        else {
            throw new \Exception("Failed to get certificate information {$this->company->certificate_name}.", 1);
        }
    }

    /**
     * Ret C14 digest sha1
     * @param  string $xmlString
     * @return string
     */
    public function retC14DigestSha1($xmlString){
        $DOMDocument = new DOMDocument('1.0', 'UTF-8');
        $DOMDocument->loadXML(str_replace("\r", '', str_replace("\n", '', $xmlString)));

        return base64_encode(sha1($DOMDocument->C14N(), true));
    }

    /**
     * Send
     * @return void
     */
    public function send() {
        $this->document->refresh();

        $curl = $this->prepareCurl($this->company, view('dian.send', [
            'company' => $this->company,
            'document' => $this->document,
            'zipBase64' => $this->zip()
        ])->render());

        $this->deleteZip();

        if (($response = curl_exec($curl)) === false) {
            LogDocument::create([
                'document_id' => $this->document->id,
                'state_document_id' => $this->document->state_document_id,
                'payload' => curl_error($curl)
            ]);

            return;
        }

        $this->saveLog($response);
    }

    /**
     * Zip
     * @return string
     */
    private function zip() {
        try {
            $zip = new ZipArchive();
            $nameZip = str_replace('.xml', '.zip', $xmlPach = Storage::disk('tenant')->path($this->getPathFile()));

            $zip->open($nameZip, ZipArchive::CREATE);

            $zip->addFile($xmlPach, pathinfo($xmlPach, PATHINFO_BASENAME));
            $zip->close();

            return base64_encode(file_get_contents($nameZip));
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    /**
     * Delete zip
     * @return void
     */
    private function deleteZip() {
        try {
            Storage::disk('tenant')->delete($this->getPathFile('zip'));
        }
        catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    /**
     * Query
     * @param  \App\Models\Tenant\Document $document
     * @return \Illuminate\Http\Response
     */
    public function query(Document $document) {
        $this->company = $this->company ?? Company::firstOrFail();
        $this->document = $document;

        if ($document->state_document_id != 6) {
            $curl = $this->prepareCurl(Company::firstOrFail(), view('dian.query', [
                'company' => $this->company,
                'document' => $this->document
            ])->render());

            if (($response = curl_exec($curl)) === false) {
                LogDocument::create([
                    'document_id' => $this->document->id,
                    'state_document_id' => $this->document->state_document_id,
                    'payload' => curl_error($curl)
                ]);

                return;
            }

            $this->saveLog($response);
        }

        return [
            'success' => true,
            'document' => Document::query()
                ->with('state_document', 'currency', 'type_document', 'detail_documents', 'reference', 'log_documents')
                ->where('id', $this->document->id)
                ->first()
        ];
    }

    /**
     * Prepare curl
     * @param  \App\Models\Tenant\Company $company
     * @param  string  $xmlString
     * @return curl
     */
    private function prepareCurl(Company $company, string $xmlString) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $company->ambient->url);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 180);
        curl_setopt($curl, CURLOPT_TIMEOUT, 180);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlString);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-type: text/xml;charset=\"utf-8\"",
            "SOAPAction: \"EnvioFacturaElectronicaPeticion\"",
            "Content-length: ".strlen($xmlString)
        ]);

        return $curl;
    }

    /**
     * Save log
     * @param  string $response
     * @return void
     */
    private function saveLog($response) {
        $logDocument = LogDocument::create([
            'document_id' => $this->document->id,
            'state_document_id' => $this->document->state_document_id,
            'payload' => $response
        ]);

        $star = strrpos($logDocument->payload, '<SOAP-ENV:Envelope');
        $end = strrpos($logDocument->payload, '</SOAP-ENV:Envelope>');

        if ($star !== false) {
            $xml = simplexml_load_string(str_ireplace(['SOAP-ENV:', 'SOAP:', 'wsse:', 'ds:', 'wsu:', 'ns2:', 'ns3:'], '', substr($logDocument->payload, $star, (($end - $star) + strlen('</SOAP-ENV:Envelope>')))));

            if ($xml->Body->EnvioFacturaElectronicaRespuesta) {
                $code = (string) optional($xml->Body->EnvioFacturaElectronicaRespuesta)->Response;

                if (array_key_exists($code, $this->responseCodes)) $logDocument->update(['payload' => $this->responseCodes[$code]]);
                if ($code == 200) $this->document->update(['state_document_id' => 2]);
                if (($code != 200) && ($code != 500)) $this->document->update(['state_document_id' => 6]);
            }

            if ($xml->Body->ConsultaResultadoValidacionDocumentosRespuesta) {
                $codeTransaction = (string) $xml->Body->ConsultaResultadoValidacionDocumentosRespuesta->CodigoTransaccion;

                if (($codeTransaction == 320) && (array_key_exists($codeTransaction, $this->descriptionTransactionQuery))) {
                    $logDocument->update([
                        'payload' => $this->descriptionTransactionQuery[$codeTransaction]
                    ]);

                    $this->document->update([
                        'state_document_id' => 1
                    ]);

                    return;
                }

                if ((($codeTransaction == 300) || ($codeTransaction == 310)) && (array_key_exists($codeTransaction, $this->descriptionTransactionQuery))) {
                    $logDocument->update([
                        'payload' => $this->descriptionTransactionQuery[$codeTransaction]
                    ]);

                    return;
                }

                $code = (string) optional(optional(optional($xml->Body->ConsultaResultadoValidacionDocumentosRespuesta)->DocumentoRecibido)->DatosBasicosDocumento)->EstadoDocumento;

                if (array_key_exists($code, $this->responseCodesQuery)) $logDocument->update(['payload' => $this->responseCodesQuery[$code]]);
                if ($code == 7200001) $this->document->update(['state_document_id' => 3]);
                if ($code == 7200002) $this->document->update(['state_document_id' => 5]);
                if ($code == 7200003) $this->document->update(['state_document_id' => 4]);
                if (($code == 7200004) || ($code == 7200005)) $this->document->update(['state_document_id' => 6]);
            }
        }
    }

    /**
     * Send email
     * @param  \App\Models\Tenant\Document $document
     * @param  \App\Models\Tenant\Client   $client
     * @return \Illuminate\Http\Response
     */
    public function sendEmail(Document $document, Client $client) {
        if ($client->email != null) {
            try {
                Mail::to($client->email)
                    ->send(new SendGraphicRepresentation(Company::firstOrFail(), $document));
            }
            catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }

            return [
                'success' => true,
                'message' => "Se envio el correo electrónico con exíto."
            ];
        }
        else {
            return [
                'success' => false,
                'message' => "El cliente {$client->name} no tiene correo electrónico."
            ];
        }

    }

    /**
     * Upload certificate
     * @param  File
     * @return void
     */
    public function uploadCertificate($certificate) {
        Storage::disk('tenant')->put("certificates/{$certificate->getClientOriginalName()}", file_get_contents($certificate->path()));
    }

    /**
     * Download document
     * @param  string   $type
     * @param  \App\Models\Tenant\Document $document
     * @return file
     */
    public function downloadDocument($type, Document $document) {
        return Storage::disk('tenant')->download("{$document->type_document->template}/{$document->xml}", "{$document->prefix}{$document->number}.{$type}");
    }

    protected function validarDigVerifDIAN($nit)
    {
        if(is_numeric(trim($nit))){
            $secuencia = array(3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71);
            $d = str_split(trim($nit));
            krsort($d);
            $cont = 0;
            unset($val);
            foreach ($d as $key => $value) {
                $val[$cont] = $value * $secuencia[$cont];
                $cont++;
            }
            $suma = array_sum($val);
            $div = intval($suma / 11);
            $num = $div * 11;
            $resta = $suma - $num;
            if ($resta == 1)
                return $resta;
            else
                if($resta != 0)
                    return 11 - $resta;
                else
                    return $resta;
        } else {
            return FALSE;
        }
    }

    
    /**
     * Retorna array con mensaje de error y registra detalle en el log
     *
     * @param  string $message
     * @param  Exception $exception
     * @return array
     */
    public function getErrorFromException($message, Exception $exception) {

        $this->setErrorLog($exception);

        return [
            'success' => false,
            'message' => $message
        ];

    }

    
    public function setErrorLog($exception)
    {
        Log::error("Code: {$exception->getCode()} - Line: {$exception->getLine()} - Message: {$exception->getMessage()} - File: {$exception->getFile()}");
    }


}
