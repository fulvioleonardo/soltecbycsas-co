<?php

namespace Modules\Factcolombia1\Exports\Tenant;

use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithTitle
};
use Modules\Factcolombia1\Models\Tenant\Item;

class ItemsFormatExport implements FromCollection, WithHeadings, WithTitle
{
    public function headings(): array {
        return [
            'Nombre',
            'Código interno',
            'Código tipo de unidad',
            'Precio',
            'Código del impuesto'
        ];
    }

    public function title(): string {
        return 'Formato';
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() {
        return Item::query()
            ->select('name', 'code', 'type_unit_id', 'price', 'tax_id')
            ->get()
            ->random(1);
    }
}
