<?php

namespace Modules\Report\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class TaxExport implements  FromView, ShouldAutoSize
{
    use Exportable;

    public function records($records) {
        $this->records = $records;

        return $this;
    }

    public function company($company) {
        $this->company = $company;

        return $this;
    }

    public function establishment($establishment) {
        $this->establishment = $establishment;

        return $this;
    }

    public function taxitles($taxTitles) {
        $this->taxTitles = $taxTitles;

        return $this;
    }

    public function taxesall($taxesAll) {
        $this->taxesAll = $taxesAll;

        return $this;
    }



    public function view(): View {
        return view('report::tax.report_excel', [
            'records'=> $this->records,
            'company' => $this->company,
            'establishment'=>$this->establishment,
            'taxTitles' => $this->taxTitles,
            'taxesAll' => $this->taxesAll,
        ]);
    }
}
