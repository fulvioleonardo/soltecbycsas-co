<?php

namespace Modules\Factcolombia1\Mail\Tenant;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Modules\Factcolombia1\Models\Tenant\{
    TypeIdentityDocument,
    Document,
    Company
};

use Modules\Factcolombia1\Models\TenantService\{
    Company as TenantServiceCompany
};
use Mpdf\Mpdf;
use Storage;

class SendGraphicRepresentation extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * Company
     * @var \App\Models\Tenant\Company
     */
    public $company;
    
    /**
     * Document
     * @var \App\Models\Tenant\Document
     */
    public $document;

    /**
     * TenantServiceCompany
     * @var \App\Models\TenantService\Company
     */
    public $servicecompany;

    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Company $company, Document $document, TenantServiceCompany $servicecompany) {
        $this->company = $company;
        $this->document = $document;
        $this->servicecompany = $servicecompany;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $mpdf = new Mpdf([
            'tempDir' => storage_path('mpdf')
        ]);
        
        $mpdf->WriteHTML(view("pdf/{$this->document->type_document->template}", [
            'typeIdentityDocuments' => TypeIdentityDocument::all(),
            'company' => $this->company,
            'document' => $this->document,
            'servicecompany' => $this->servicecompany
        ])->render());
        
        return $this->from($this->company->email, $this->company->name)
        ->markdown('emails.tenant.send.graphicRepresentation')
        ->subject("{$this->company->name} - {$this->document->type_document->name} {$this->document->prefix}{$this->document->number}")
        ->attachData($mpdf->Output(null, 'S'), "{$this->document->prefix}{$this->document->number}.pdf");
      //  ->attach(Storage::disk('tenant')->path("{$this->document->type_document->template}/{$this->document->xml}"));
    }
}
