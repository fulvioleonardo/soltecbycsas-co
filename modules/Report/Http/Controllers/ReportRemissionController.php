<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\RemissionExport;
use Illuminate\Http\Request;
use Modules\Report\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Sale\Http\Resources\RemissionCollection;
use Modules\Sale\Models\Remission;

class ReportRemissionController extends Controller
{

    use ReportTrait;

    public function filter() {

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        return compact('establishments');
    }


    public function index() {

        return view('report::co-remissions.index');
    }


    public function records(Request $request)
    {
        $records = $this->getRecords($request->all(), Remission::class);

        return new RemissionCollection($records->paginate(config('tenant.items_per_page')));
    }



    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), Remission::class)->get();

        $pdf = PDF::loadView('report::co-remissions.report_pdf', compact("records", "company", "establishment"));

        $filename = 'Reporte_Remisiones_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }




    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecords($request->all(), Remission::class)->get();

        return (new RemissionExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('Reporte_Remisiones_'.Carbon::now().'.xlsx');

    }
}
