<?php

namespace Modules\Report\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DocumentPosExport implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    public function data($data) 
    {
        $this->data = $data;
        return $this;
    }
    
    public function view(): View {
        return view('report::document_pos.report_excel', $this->data);
    }
    
}
