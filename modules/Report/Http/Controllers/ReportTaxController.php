<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\OrderNoteConsolidatedCollection;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPos;
use App\Models\Tenant\DocumentPosItem;
use Modules\Report\Exports\TaxExport;




class ReportTaxController extends Controller
{


    public function index() {

        return view('report::tax.index');
    }

    public function records(Request $request)
    {
        $taxesAll = collect();

        $documents = Document::query()
            ->with('type_document', 'reference')
            ->whereBetween('date_of_issue', [
                Carbon::parse($request->date_start)->startOfDay()->format('Y-m-d H:m:s'),
                Carbon::parse($request->date_end)->endOfDay()->format('Y-m-d H:m:s')
            ])
            ->get();

        $documents->pluck('taxes')->each(function($taxes) use($taxesAll) {
            collect($taxes)->each(function($tax) use($taxesAll) {
                $taxesAll->push($tax);
            });
        });

        $taxTitles = $taxesAll->unique('id');

        $documents_pos = DocumentPos::query()
            ->whereBetween('date_of_issue', [
                Carbon::parse($request->date_start)->startOfDay()->format('Y-m-d H:m:s'),
                Carbon::parse($request->date_end)->endOfDay()->format('Y-m-d H:m:s')
            ])
            ->get();

       // $union = $documents->union( $documents_pos );

        return [
            'success' => true,
            'data' => array_merge($documents->toArray(), $documents_pos->toArray()),
            'taxTitles' => $taxTitles->values(),
            'taxesAll' => $taxesAll
        ];

    }

    public function excel(Request $request)
    {
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $taxesAll = collect();

        $documents = Document::
            with('type_document', 'reference')
            ->whereBetween('date_of_issue', [
                Carbon::parse($request->date_start)->startOfDay()->format('Y-m-d H:m:s'),
                Carbon::parse($request->date_end)->endOfDay()->format('Y-m-d H:m:s')
            ])
            ->get();


        $documents->pluck('taxes')->each(function($taxes) use($taxesAll) {
            collect($taxes)->each(function($tax) use($taxesAll) {
                $taxesAll->push($tax);
            });
        });

        $documents_pos = DocumentPos::
            whereBetween('date_of_issue', [
                Carbon::parse($request->date_start)->startOfDay()->format('Y-m-d H:m:s'),
                Carbon::parse($request->date_end)->endOfDay()->format('Y-m-d H:m:s')
            ])
            ->get();

        $taxTitles = $taxesAll->unique('id')->values();

        //$records =  //(object)array_merge($documents->toArray(), $documents_pos->toArray());

        return (new TaxExport)
                ->records($documents)
                ->company($company)
                ->establishment($establishment)
                ->taxitles($taxTitles)
                ->taxesall($taxesAll)
                ->download('Reporte_Impuestos_'.Carbon::now().'.xlsx');

    }





    /*public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecordsOrderNotes($request->all(), OrderNoteItem::class)->get();
        $params = $request->all();

        $pdf = PDF::loadView('report::order_notes_consolidated.report_pdf', compact("records", "company", "establishment", "params"));

        $filename = 'Reporte_Consolidado_Items_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }*/
    public function downloadDocumentPos(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        //$taxesAll = collect();

        $records = DocumentPos::select('id', 'date_of_issue', 'total', 'subtotal')->whereBetween('date_of_issue', [
                Carbon::parse($request->date_start)->startOfDay(),
                Carbon::parse($request->date_end)->endOfDay()
            ])
            ->get();

        $records = DocumentPos::select('id', 'date_of_issue', 'total', 'subtotal')->get();

        $items = collect( DocumentPosItem::whereIn('document_pos_id', $records->pluck('id'))->get() );

        //return $items;

        $total_sale = $records->sum('total');
        $total_sale_base = $records->sum('subtotal');

       // return $total_sale_base;

       // $taxTitles = $taxesAll->unique('id');

        $pdf = PDF::loadView('report::tax.report_pos_pdf', compact("company", "establishment", 'total_sale', 'items', 'total_sale_base'));

        $filename = 'Informe_Fiscal_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }

}
