<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\DocumentPosExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportDocumentTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\DocumentPos;
use Carbon\Carbon;
use Modules\Report\Http\Resources\DocumentPosCollection;
use Modules\Factcolombia1\Models\Tenant\{
    TypeDocument,
};


class ReportDocumentPosController extends Controller
{

    use ReportDocumentTrait;

    public function filter() 
    {
        $persons = $this->getPersons('customers');
        $sellers = $this->getSellers();
        $establishments = $this->getTransformEstablishments();

        return compact('establishments','persons', 'sellers');
    }


    public function index() 
    {
        return view('report::document_pos.index');
    }
    

    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), DocumentPos::class);

        return new DocumentPosCollection($records->paginate(config('tenant.items_per_page')));
    }
    
    /**
     * 
     * Obtener datos para renderizar formato pdf/excel
     *
     * @param  Request $request
     * @return array
     */
    private function getDataForReport(Request $request)
    {
        return [
            'company' => $this->getCompanyReport(),
            'establishment' => $this->getEstablishmentForReport($request->establishment_id),
            'records' => $this->getRecords($request->all(), DocumentPos::class)->get(),
        ];
    }


    /**
     * Reporte formato pdf
     *
     * @param  Request $request
     */
    public function pdf(Request $request)
    {
        $pdf = PDF::loadView('report::document_pos.report_pdf', $this->getDataForReport($request));
        return $pdf->download($this->getFilenameReport('Reporte_Ventas_POS'));
    }

    
    /**
     * Reporte formato excel
     *
     * @param  Request $request
     */
    public function excel(Request $request) 
    {
        return (new DocumentPosExport)
                ->data($this->getDataForReport($request))
                ->download($this->getFilenameReport('Reporte_Ventas_POS', 'xlsx'));
    }

}
