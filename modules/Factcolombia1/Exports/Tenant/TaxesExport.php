<?php

namespace Modules\Factcolombia1\Exports\Tenant;

use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Factcolombia1\Models\Tenant\Tax;

class TaxesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        return Tax::all();
    }
}
