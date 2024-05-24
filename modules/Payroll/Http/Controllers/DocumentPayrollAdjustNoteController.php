<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payroll\Models\{
    DocumentPayrollAdjustNote,
    DocumentPayroll,
    Worker
};
use Modules\Payroll\Http\Resources\{
    DocumentPayrollAdjustNoteResource
};
use Modules\Payroll\Http\Requests\DocumentPayrollAdjustNoteRequest;
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


class DocumentPayrollAdjustNoteController extends Controller
{
    
    use UtilityTrait;

    public function create($id)
    {
        $type_payroll_adjust_note_id = DocumentPayrollAdjustNote::ADJUST_NOTE_REPLACE_ID;

        return view('payroll::document-payrolls.form', compact('id', 'type_payroll_adjust_note_id'));
    }

 
    public function tables($type_payroll_adjust_note_id)
    {

        $resolutions = TypeDocument::select('id', 'prefix', 'resolution_number')->where('code', DocumentPayroll::ADJUST_NOTE_TYPE_DOCUMENT_ID)->get();

        // nomina eliminacion
        if($type_payroll_adjust_note_id == DocumentPayrollAdjustNote::ADJUST_NOTE_ELIMINATION_ID)
        {
            return [
                'resolutions' => $resolutions
            ];
        }

        // nomina de reemplazo
        
        return [
            'workers' => [],
            'payroll_periods' => PayrollPeriod::get(),
            'type_disabilities' => TypeDisability::get(),
            'payment_methods' => PaymentMethod::get(),
            'type_law_deductions' => TypeLawDeductions::whereTypeLawDeductionsWorker()->get(),
            'advanced_configuration' => AdvancedConfiguration::first(),
            'resolutions' => $resolutions
        ];

    }
        
    /**
     * Buscar nómina afectada
     *
     * @param  int $id
     * @return DocumentPayrollAdjustNoteResource
     */
    public function record($id)
    {
        return new DocumentPayrollAdjustNoteResource(DocumentPayroll::with(['accrued', 'deduction'])->findOrFail((int) $id));
    }
    
     
    /**
     * 
     * Registar nómina de eliminación/reemplazo
     *
     * @param  DocumentPayrollAdjustNoteRequest $request
     * @return array
     */
    public function store(DocumentPayrollAdjustNoteRequest $request)
    {

        try {

            $data = DB::connection('tenant')->transaction(function () use($request) {
    
                // inputs
                $helper = new DocumentPayrollHelper();
                $inputs = $helper->getInputsAdjustNote($request);
                // dd($inputs);

                // registrar nomina en bd
                $document = DocumentPayroll::create($inputs);
                $document->adjust_note()->create($inputs['adjust_note']);

                // si es nómina reemplazo, registrar devengados y deducciones
                if(!$document->adjust_note->is_adjust_note_elimination)
                {
                    $document->accrued()->create($inputs['accrued']);
                    $document->deduction()->create($inputs['deduction']);
                }
    
                // enviar nomina ajuste a la api
                $send_to_api = $helper->sendToApi($document, $inputs);
    
                $document->update([
                    'response_api' => $send_to_api
                ]);
    
                return $document;
            });

            $message = $data->adjust_note->is_adjust_note_elimination ? "Nómina de eliminación {$data->number_full} registrada con éxito" : "Nómina de reemplazo {$data->number_full} registrada con éxito";
    
            return [
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $data->id
                ]
            ];

        } catch (Exception $e)
        {
            return $this->getErrorFromException($e->getMessage(), $e);
        }

    }
 
        
}
