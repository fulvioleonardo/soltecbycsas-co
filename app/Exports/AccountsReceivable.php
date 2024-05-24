<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\SaleNotePayment;
use App\Models\Tenant\Invoice;
use Carbon\Carbon;
use Modules\Factcolombia1\Models\Tenant\{
    Currency,
};


class AccountsReceivable implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    /**/

        $document_payments = DB::table('document_payments')
                                ->select('document_id', DB::raw('SUM(payment) as total_payment'))
                                ->groupBy('document_id');
                
        $documents = DB::connection('tenant')
                        ->table('documents')
                        ->join('persons', 'persons.id', '=', 'documents.customer_id')
                        ->leftJoinSub($document_payments, 'payments', function ($join) {
                            $join->on('documents.id', '=', 'payments.document_id');
                        })
                        ->whereIn('state_document_id', [1, 2, 3, 4, 5])
                        ->whereIn('type_document_id', [1, 2])
                        ->select(DB::raw("documents.id as id, ".
                                            "DATE_FORMAT(documents.date_of_issue, '%Y-%m-%d') as date_of_issue, ".
                                            "persons.name as customer_name, persons.id as customer_id, documents.type_document_id, ".
                                            "CONCAT(documents.prefix,'-',documents.number) AS number_full, ".
                                            "documents.total as total, ".
                                            "IFNULL(payments.total_payment, 0) as total_payment, ".
                                            "'document' AS 'type', ". "documents.currency_id, ". "documents.date_expiration"))
                        ->where('total_canceled', 0);



        $sale_note_payments = DB::table('sale_note_payments')
                                ->select('sale_note_id', DB::raw('SUM(payment) as total_payment'))
                                ->groupBy('sale_note_id');
            

        $company = DB::connection('tenant')->table('companies')
                            ->select('name', 'number')->get();


        $sale_notes = DB::connection('tenant')
                        ->table('sale_notes')
                        ->join('persons', 'persons.id', '=', 'sale_notes.customer_id')
                        ->leftJoinSub($sale_note_payments, 'payments', function ($join) {
                            $join->on('sale_notes.id', '=', 'payments.sale_note_id');
                        })
                        ->whereIn('state_type_id', ['01','03','05','07','13'])
                        ->select(DB::raw("sale_notes.id as id, ".
                                        "DATE_FORMAT(sale_notes.date_of_issue, '%Y-%m-%d') as date_of_issue, ".
                                        "persons.name as customer_name, persons.id as customer_id, null as type_document_id,".
                                        "CONCAT(sale_notes.series,'-',sale_notes.number) AS number_full, ".
                                        "sale_notes.total as total, ".
                                        "IFNULL(payments.total_payment, 0) as total_payment, ".
                                        "'sale_note' AS 'type', " . "sale_notes.currency_id, ". "null as date_expiration"))
                        ->where('sale_notes.changed', false)
                        ->where('sale_notes.total_canceled', false);


        $records = $documents->union($sale_notes)->get();

        $collection =  collect($records)->transform(function($row) {

            $total_to_pay = (float)$row->total - (float)$row->total_payment;
            $delay_payment = null;
            $date_of_due = null;
            
            
            if($total_to_pay > 0) {

                if($row->date_expiration){

                    $due =   Carbon::parse($row->date_expiration); // $invoice->date_of_due;
                    $date_of_due = $row->date_expiration;
                    $now = Carbon::now();

                    if($now > $due){

                        $delay_payment = $now->diffInDays($due);
                    }

                }

            }

            $guides = null;
            $date_payment_last = '';

            if($row->type_document_id){ 

                $date_payment_last = DocumentPayment::where('document_id', $row->id)->orderBy('date_of_payment', 'desc')->first();
            }
            else{
                $date_payment_last = SaleNotePayment::where('sale_note_id', $row->id)->orderBy('date_of_payment', 'desc')->first();
            }


            return [
                'id' => $row->id,
                'date_of_issue' => $row->date_of_issue,
                'customer_name' => $row->customer_name,
                'customer_id' => $row->customer_id,
                'number_full' => $row->number_full,
                'total' => number_format((float) $row->total,2, ".", ""),
                'total_to_pay' => number_format($total_to_pay,2, ".", ""),
                'type' => $row->type,
                'date_payment_last' => ($date_payment_last) ? $date_payment_last->date_of_payment->format('Y-m-d') : null,
                'delay_payment' => $delay_payment,
                'date_of_due' =>  $date_of_due,
                'currency_id' => Currency::select('name')->find($row->currency_id)->name, 
            ];
        });
        
        return view('tenant.reports.no_paid.reportall_excel',['records' => $collection->all(),
            'companies' => $company ]);
         
    }

}
