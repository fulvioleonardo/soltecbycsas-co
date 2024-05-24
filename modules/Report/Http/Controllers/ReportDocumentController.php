<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\DocumentExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportDocumentTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\DocumentCollection;
use Modules\Factcolombia1\Models\Tenant\{
    TypeDocument,
};


class ReportDocumentController extends Controller
{
    use ReportDocumentTrait;


    public function filter() {

        $document_types = TypeDocument::query()
                            ->get()
                            ->each(function($typeDocument) {
                                $typeDocument->alert_range = (($typeDocument->to - 100) < (Document::query()
                                    ->hasPrefix($typeDocument->prefix)
                                    ->whereBetween('number', [$typeDocument->from, $typeDocument->to])
                                    ->max('number') ?? $typeDocument->from));

                                $typeDocument->alert_date = ($typeDocument->resolution_date_end == null) ? false : Carbon::parse($typeDocument->resolution_date_end)->subMonth(1)->lt(Carbon::now());
                            })->transform(function($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => $row->name
                                ];
                            });

        $persons = $this->getPersons('customers');
        $sellers = $this->getSellers();

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        return compact('document_types','establishments','persons', 'sellers');
    }


    public function index() {

        return view('report::documents.index');
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), Document::class);

        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }



    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), Document::class)->get();

        $pdf = PDF::loadView('report::documents.report_pdf', compact("records", "company", "establishment"));

        $filename = 'Reporte_Ventas_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }




    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecords($request->all(), Document::class)->get();

        return (new DocumentExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('Reporte_Ventas_'.Carbon::now().'.xlsx');

    }
}
