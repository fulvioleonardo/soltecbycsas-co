<?php

namespace Modules\Factcolombia1\Exports\Tenant;

use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Factcolombia1\Models\Tenant\Item;

class ItemsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        return Item::all();
    }
}
