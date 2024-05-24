<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class PersonExport implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    public function records($records) {
        $this->records = $records;
        
        return $this;
    }
    
    
    public function view(): View {
        return view('tenant.persons.export_excel', [
            'records'=> $this->records,
        ]);
    }
}
