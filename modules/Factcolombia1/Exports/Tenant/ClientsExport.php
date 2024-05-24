<?php

namespace Modules\Factcolombia1\Exports\Tenant;

use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Factcolombia1\Models\Tenant\Client;

class ClientsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        return Client::all();
    }
}
