<xades:SignedProperties Id="xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a-signedprops">
    <xades:SignedSignatureProperties>
        <xades:SigningTime>{{Carbon\Carbon::now()->format('Y-m-d\TH:i:s.vT:00')}}</xades:SigningTime>{{-- date('Y-m-d\TH:i:s.vT:00') --}}
        <xades:SigningCertificate>
            <xades:Cert>
                <xades:CertDigest>
                    <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                    <ds:DigestValue>{{base64_encode(openssl_x509_fingerprint($infoCert["cert"], "sha1", true))}}</ds:DigestValue>
                </xades:CertDigest>
                <xades:IssuerSerial>
                    <ds:X509IssuerName>{{join(',', collect(array_reverse(openssl_x509_parse($infoCert["cert"])['issuer']))->map(function($row, $key) {return "{$key}={$row}";})->toArray())}}</ds:X509IssuerName>
                    <ds:X509SerialNumber>{{openssl_x509_parse($infoCert["cert"])['serialNumber']}}</ds:X509SerialNumber>
                </xades:IssuerSerial>
            </xades:Cert>
            <xades:Cert>
                <xades:CertDigest>
                    <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                    <ds:DigestValue>{{base64_encode(openssl_x509_fingerprint($infoCert["extracerts"][0], "sha1", true))}}</ds:DigestValue>
                </xades:CertDigest>
                <xades:IssuerSerial>
                    <ds:X509IssuerName>{{join(',', collect(array_reverse(openssl_x509_parse($infoCert['extracerts'][0])['issuer']))->map(function($row, $key) {return "{$key}={$row}";})->toArray())}}</ds:X509IssuerName>
                    <ds:X509SerialNumber>{{openssl_x509_parse($infoCert['extracerts'][0])['serialNumber']}}</ds:X509SerialNumber>
                </xades:IssuerSerial>
            </xades:Cert>
        </xades:SigningCertificate>
        <xades:SignaturePolicyIdentifier>
            <xades:SignaturePolicyId>
                <xades:SigPolicyId>
                    <xades:Identifier>https://facturaelectronica.dian.gov.co/politicadefirma/v2/politicadefirmav2.pdf</xades:Identifier>
                </xades:SigPolicyId>
                <xades:SigPolicyHash>
                    <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                    <ds:DigestValue>sbcECQ7v+y/m3OcBCJyvmkBhtFs=</ds:DigestValue>
                </xades:SigPolicyHash>
            </xades:SignaturePolicyId>
        </xades:SignaturePolicyIdentifier>
        <xades:SignerRole>
            <xades:ClaimedRoles>
                <xades:ClaimedRole>supplier</xades:ClaimedRole>
            </xades:ClaimedRoles>
        </xades:SignerRole>
    </xades:SignedSignatureProperties>
</xades:SignedProperties>