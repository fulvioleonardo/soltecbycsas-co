<ds:SignedInfo>
    <ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
    <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
    <ds:Reference Id="xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a-ref0" URI="">
        <ds:Transforms>
            <ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
        </ds:Transforms>
        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
        <ds:DigestValue>{{$canonizadorealBase64}}</ds:DigestValue>
    </ds:Reference>
    <ds:Reference Id="xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a-ref1" URI="#KeyInfo">
        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
        <ds:DigestValue>{{$keyString}}</ds:DigestValue>
    </ds:Reference>
    <ds:Reference Type="http://uri.etsi.org/01903#SignedProperties" URI="#xmldsig-ab2df1fb-1819-413d-8b8c-79e9ed75638a-signedprops">
        <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
        <ds:DigestValue>{{$signString}}</ds:DigestValue>
    </ds:Reference>
</ds:SignedInfo>