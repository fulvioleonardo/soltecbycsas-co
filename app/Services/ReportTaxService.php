<?php

namespace App\Services;
use App\Models\Tenant\Catalogs\DetractionType;
use App\Models\Tenant\Catalogs\PaymentMethodType;


class ReportTaxService
{

    public function getTaxTotalBill($tax, $taxes)
    {
        $row = collect($taxes)->firstWhere('id', $tax->id);
        return ($row) ? ( ($row->is_retention) ? $row->retention : $row->total ) : 0;
    }

    public function getTaxTotal($tax, $taxesAll)
    {
        $sum = 0;
        $rows = collect($taxesAll)->where('id', $tax->id);
        foreach ($rows as $row) {

            $tax->is_retention ? $sum += $row->retention :  $sum += $row->total;

        }

        return $sum;
    }

}
