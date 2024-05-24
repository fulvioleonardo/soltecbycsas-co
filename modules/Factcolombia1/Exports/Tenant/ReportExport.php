<?php

namespace Modules\Factcolombia1\Exports\Tenant;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{
    FromView
};
use App\Models\Tenant\Document;
use Carbon\Carbon;

class ReportExport implements FromView
{
    /**
     * Construct
     * @param string $dateFrom
     * @param string $dateUp
     */
    public function __construct($dateFrom, $dateUp) {
        $this->dateFrom = $dateFrom;
        $this->dateUp = $dateUp;
    }
    
    /**
     * View
     * @return View
     */
    public function view() : View {
        $taxesAll = collect();
        
        $documents = Document::query()
            ->with('type_document', 'reference')
            ->whereBetween('date_issue', [
                Carbon::parse($this->dateFrom)->startOfDay()->format('Y-m-d H:m:s'),
                Carbon::parse($this->dateUp)->endOfDay()->format('Y-m-d H:m:s')
            ])
            ->get();
        
        $documents->pluck('taxes')->each(function($taxes) use($taxesAll) {
            collect($taxes)->each(function($tax) use($taxesAll) {
                $taxesAll->push($tax);
            });
        });
        
        $taxTitles = $taxesAll->unique('id');
        
        return view('report/taxes', compact('taxTitles', 'documents', 'taxesAll'));
    }
}
