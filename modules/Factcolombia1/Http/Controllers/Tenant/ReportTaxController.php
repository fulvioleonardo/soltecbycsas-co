<?php

namespace Modules\Factcolombia1\Http\Controllers\Tenant;

use Modules\Factcolombia1\Http\Requests\Tenant\ReportTaxRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Factcolombia1\Http\Controllers\Controller;
use Modules\Factcolombia1\Exports\Tenant\ReportExport;
use Modules\Factcolombia1\Models\Tenant\Document;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('report.tenant.index');
    }

    /**
     * Data
     * @param  \App\Http\Requests\Tenant\ReportTaxRequest\ReportTaxRequest $request
     * @return \Illuminate\Http\Response
     */
    public function data(ReportTaxRequest $request) {
        $taxesAll = collect();

        $documents = Document::query()
            ->with('type_document', 'reference')
            ->whereBetween('date_issue', [
                Carbon::parse($request->date_from)->startOfDay()->format('Y-m-d H:m:s'),
                Carbon::parse($request->date_up)->endOfDay()->format('Y-m-d H:m:s')
            ])
            ->get();

        $documents->pluck('taxes')->each(function($taxes) use($taxesAll) {
            collect($taxes)->each(function($tax) use($taxesAll) {
                $taxesAll->push($tax);
            });
        });

        $taxTitles = $taxesAll->unique('id');

        return [
            'success' => true,
            'documents' => $documents,
            'taxTitles' => $taxTitles,
            'taxesAll' => $taxesAll
        ];
    }

    /**
     * Export
     * @param  \App\Http\Requests\Tenant\ReportTaxRequest\ReportTaxRequest $request
     * @return \Illuminate\Http\Response
     */
    public function export(ReportTaxRequest $request) {
        return Excel::download(new ReportExport($request->date_from, $request->date_up), 'Reporte impuestos '.Carbon::now().'.xlsx');
    }
}
