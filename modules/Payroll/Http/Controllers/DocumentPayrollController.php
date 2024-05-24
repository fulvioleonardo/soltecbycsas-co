<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payroll\Models\{
    DocumentPayroll,
    Worker
};
use Modules\Payroll\Http\Resources\{
    DocumentPayrollCollection,
    DocumentPayrollResource
};
use Modules\Payroll\Http\Requests\DocumentPayrollRequest;
use Modules\Factcolombia1\Models\TenantService\{
    PayrollPeriod,
    TypeLawDeductions,
    TypeDisability,
    AdvancedConfiguration,
    TypeOvertimeSurcharge,
};
use Modules\Factcolombia1\Models\Tenant\{
    PaymentMethod,
    TypeDocument,
};
use Illuminate\Support\Facades\DB;
use Exception;
use Modules\Payroll\Helpers\DocumentPayrollHelper;
use Modules\Factcolombia1\Http\Controllers\Tenant\DocumentController;
use Modules\Payroll\Traits\UtilityTrait; 


class DocumentPayrollController extends Controller
{
    
    use UtilityTrait;

    public function index()
    {
        return view('payroll::document-payrolls.index');
    }

    public function create()
    {
        return view('payroll::document-payrolls.form');
    }

    public function columns()
    {
        return [
            'consecutive' => 'Número',
            'date_of_issue' => 'Fecha de emisión',
        ];
    }
 
    public function tables()
    {
        return [
            'workers' => $this->table('workers'),
            'payroll_periods' => PayrollPeriod::get(),
            'type_disabilities' => TypeDisability::get(),
            'payment_methods' => PaymentMethod::get(),
            'type_law_deductions' => TypeLawDeductions::whereTypeLawDeductionsWorker()->get(),
            'advanced_configuration' => AdvancedConfiguration::first(),
            // 'type_documents' => TypeDocument::get(),
            'resolutions' => TypeDocument::select('id','prefix', 'resolution_number')->where('code', 9)->get()
        ];
    }

    public function table($table)
    {

        if($table == 'workers') 
        {
            return Worker::take(20)->get()->transform(function($row){
                return $row->getSearchRowResource();
            });
        }

        if($table == 'type_overtime_surcharges') 
        {
            return TypeOvertimeSurcharge::get();
        }

        return [];
    }
    
    
    public function record($id)
    {
        return new DocumentPayrollResource(DocumentPayroll::findOrFail($id));
    }


    public function records(Request $request)
    {
        $records = DocumentPayroll::whereFilterRecords($request)->latest();

        return new DocumentPayrollCollection($records->paginate(config('tenant.items_per_page')));
    }
 
    
    /**
     * Consultar zipkey - usado en habilitación
     *
     * @param  Request $request
     * @return array
     */
    public function queryZipkey(Request $request)
    {

        try {

            $document = DocumentPayroll::findOrFail($request->id);
            $helper = new DocumentPayrollHelper();
            $zip_key = $document->response_api->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey;
            // dd($document);

            return $helper->validateZipKey($zip_key, $document->number_full, $document);

            
        } catch (Exception $e)
        {
            return $this->getErrorFromException($e->getMessage(), $e);
        }

    }
 
    public function store(DocumentPayrollRequest $request)
    {

        try {

            $data = DB::connection('tenant')->transaction(function () use($request) {
    
                // inputs
                $helper = new DocumentPayrollHelper();
                $inputs = $helper->getInputs($request);
                
                // registrar nomina en bd
                $document = DocumentPayroll::create($inputs);
                $document->accrued()->create($inputs['accrued']);
                $document->deduction()->create($inputs['deduction']);
    
                // enviar nomina a la api
                $send_to_api = $helper->sendToApi($document, $inputs);
    
                $document->update([
                    'response_api' => $send_to_api
                ]);
    
                return $document;
            });
    
            return [
                'success' => true,
                'message' => 'Nómina registrada con éxito',
                'data' => [
                    'id' => $data->id
                ]
            ];

        } catch (Exception $e)
        {
            return $this->getErrorFromException($e->getMessage(), $e);
        }

    }
 
        
    /**
     * Descarga de xml/pdf
     *
     * @param  string $filename
     */
    public function downloadFile($filename)
    {
        return app(DocumentController::class)->downloadFile($filename);
    }

        
    /**
     * Envio de correo de la nómina
     *
     * @param  Request $request
     * @return array
     */
    public function sendEmail(Request $request)
    {
        return (new DocumentPayrollHelper())->sendEmail($request);
    }

}
