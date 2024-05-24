<?php

namespace Modules\Dashboard\Traits;

use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNotePayment;
use Carbon\Carbon;
use App\Models\Tenant\Person;
use App\Models\Tenant\Purchase;
use Modules\Expense\Models\Expense;

trait TotalsTrait
{

    public function get_purchase_totals($establishment_id, $date_start, $date_end, $currency_id)
    {

        if($date_start && $date_end){

            $purchases = Purchase::query()->whereIn('state_type_id', ['01','03','05','07','13'])
                                        ->where('establishment_id', $establishment_id)
                                        ->whereCurrency($currency_id)
                                        ->whereBetween('date_of_issue', [$date_start, $date_end])
                                        ->get();

        }else{

            $purchases = Purchase::query()->whereIn('state_type_id', ['01','03','05','07','13'])
                                        ->where('establishment_id', $establishment_id)
                                        ->whereCurrency($currency_id)
                                        ->get();
        }


        $purchases_total = $purchases->sum('total') + $purchases->sum('total_perception');

        $purchase_total_payment = 0;

        foreach ($purchases as $purchase)
        {
            $purchase_total_payment += collect($purchase->purchase_payments)->sum('payment');
        }

        return [
            'totals' => [
                'total_payment' => round($purchase_total_payment,2),
                'total' => round($purchases_total,2),
            ]
        ];
    }


    public function get_expense_totals($establishment_id, $date_start, $date_end, $currency_id)
    {


        if($date_start && $date_end){

            $expenses = Expense::query()->where('establishment_id', $establishment_id)
                                        ->whereBetween('date_of_issue', [$date_start, $date_end])
                                        ->where('state_type_id','05')
                                        ->whereCurrency($currency_id)
                                        ->get();

        }else{

            $expenses = Expense::query()->where('establishment_id', $establishment_id)
                                        ->where('state_type_id','05')
                                        ->whereCurrency($currency_id)
                                        ->get();
        }

        $expenses_total = $expenses->sum('total');

        $expense_total_payment = 0;

        foreach ($expenses as $expense)
        {
            $expense_total_payment += collect($expense->payments)->sum('payment');
        }

        return [
            'totals' => [
                'total_payment' => round($expense_total_payment,2),
                'total' => round($expenses_total,2),
            ]
        ];

    }


    public function get_sale_note_totals($establishment_id, $date_start, $date_end, $currency_id)
    {

        if($date_start && $date_end){
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)
                                           ->whereStateTypeAccepted()
                                           ->whereCurrency($currency_id)
                                           ->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                           ->where('changed', false)
                                           ->whereStateTypeAccepted()
                                           ->whereCurrency($currency_id)
                                           ->get();
        }


        //PEN
        $sale_note_total_pen = 0;
        $sale_note_total_payment_pen = 0;

        $sale_note_total_pen = collect($sale_notes)->sum('total');

        //TWO CURRENCY
        foreach ($sale_notes as $sale_note)
        {
            $sale_note_total_payment_pen += collect($sale_note->payments)->sum('payment');
        }

        //TOTALS
        $sale_note_total = $sale_note_total_pen;
        $sale_note_total_payment = $sale_note_total_payment_pen;


        return [
            'totals' => [
                'total_payment' => round($sale_note_total_payment,2),
                'total' => round($sale_note_total,2),
            ]
        ];
    }


    public function get_document_totals($establishment_id, $date_start, $date_end, $currency_id)
    {

        if($date_start && $date_end){
            $documents = Document::query()
                                    ->whereStateTypeAccepted()
                                    ->where('establishment_id', $establishment_id)
                                    ->whereCurrency($currency_id)
                                    ->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $documents = Document::query()
                                    ->whereStateTypeAccepted()
                                    ->whereCurrency($currency_id)
                                    ->where('establishment_id', $establishment_id)
                                    ->get();
        }

        //PEN
        $document_total_pen = 0;
        $document_total_payment_pen = 0;
        $document_total_note_credit_pen = 0;

        $document_total_pen = collect($documents)->whereIn('type_document_id', [1, 2])->sum('total');

        //TWO CURRENCY

        foreach ($documents as $document)
        {
            $document_total_payment_pen += collect($document->payments)->sum('payment');
            $document_total_note_credit_pen += ($document->type_document_id == 3) ? $document->total:0; //nota de credito
        }

        //TOTALS
        $document_total = $document_total_pen;
        $document_total_note_credit = $document_total_note_credit_pen;
        $document_total_payment = $document_total_payment_pen;

        $document_total = round(($document_total - $document_total_note_credit),2);

        return [
            'totals' => [
                'total_payment' => round($document_total_payment,2),
                'total' => round($document_total,2),
            ]
        ];
    }

}
