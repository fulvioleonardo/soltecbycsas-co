<?php

namespace Modules\Dashboard\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNotePayment;
use Carbon\Carbon;
use App\Models\Tenant\Person;
use App\Models\Tenant\Item;
use App\Models\Tenant\Purchase;
use Modules\Expense\Models\Expense;
use Modules\Dashboard\Traits\TotalsTrait;
use Modules\Sale\Models\Remission;
use App\Models\Tenant\DocumentPos;

class DashboardData
{

    use TotalsTrait;

    public function data($request)
    {
// dd($request);
        $establishment_id = $request['establishment_id'];
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $currency_id = $request['currency_id'];

        $d_start = null;
        $d_end = null;

        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        return [
            'document' => $this->document_totals($establishment_id, $d_start, $d_end, $currency_id),
            'document_pos' => $this->document_pos_totals($establishment_id, $d_start, $d_end, $currency_id),
            'sale_note' => $this->sale_note_totals($establishment_id, $d_start, $d_end, $currency_id),
            'general' => $this->totals($establishment_id, $d_start, $d_end, $period, $month_start, $month_end, $currency_id),
            'balance' => $this->balance($establishment_id, $d_start, $d_end, $currency_id),
            'items' => $this->getItems(),
        ];
    }

    /**
     * @param $establishment_id
     * @param $date_start
     * @param $date_end
     * @return array
     */
    private function sale_note_totals($establishment_id, $date_start, $date_end, $currency_id)
    {

        if($date_start && $date_end){
            $sale_notes = Document::query()->where('establishment_id', $establishment_id)
                                           ->whereCurrency($currency_id)
                                           ->whereBetween('date_of_issue', [$date_start, $date_end])
                                           ->get();
        }else{
            $sale_notes = Document::query()->where('establishment_id', $establishment_id)
                                           ->whereCurrency($currency_id)
                                           ->get();
        }

        //PEN
        $sale_note_total_pen = 0;
        $sale_note_total_payment_pen = 0;

        $sale_note_total_pen = $sale_notes->sum("total"); //collect($sale_notes->where('currency_type_id', 'PEN'))->sum('total');

        //TWO CURRENCY
        foreach ($sale_notes as $sale_note)
        {
            $sale_note_total_payment_pen += collect($sale_note->payments)->sum('payment');
        }

        //TOTALS
        $sale_note_total = $sale_note_total_pen ;
        $sale_note_total_payment = $sale_note_total_payment_pen;

        $sale_note_total_to_pay = $sale_note_total - $sale_note_total_payment;

        return [
            'totals' => [
                'total_payment' => number_format($sale_note_total_payment,2, ".", ""),
                'total_to_pay' => number_format($sale_note_total_to_pay,2, ".", ""),
                'total' => number_format($sale_note_total,2, ".", ""),
            ],
            'graph' => [
                'labels' => ['Total pagado', 'Total por pagar'],
                'datasets' => [
                    [
                        'label' => 'Notas de venta',
                        'data' => [round($sale_note_total_payment,2), round($sale_note_total_to_pay,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ]
        ];
    }

    /**
     * @param $establishment_id
     * @param $date_start
     * @param $date_end
     * @return array
     */
    private function document_totals($establishment_id, $date_start, $date_end, $currency_id)
    {

        if($date_start && $date_end){
            $documents = Remission::query()->where('establishment_id', $establishment_id)
                                            ->whereCurrency($currency_id)
                                            ->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $documents = Remission::query()->where('establishment_id', $establishment_id)
                                            ->whereCurrency($currency_id)
                                            ->get();
        }

        //PEN
        $document_total_pen = 0;
        $document_total_payment_pen = 0;
        $document_total_note_credit_pen = 0;

        $document_total_pen = collect($documents)->sum('total');


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
        $document_total_to_pay = $document_total - $document_total_payment;


        return [
            'totals' => [
                'total_payment' => number_format($document_total_payment,2, ".", ""),
                'total_to_pay' => number_format($document_total_to_pay,2, ".", ""),
                'total' => number_format($document_total,2, ".", ""),
            ],
            'graph' => [
                'labels' => ['Total pagado', 'Total por pagar'],
                'datasets' => [
                    [
                        'label' => 'Comprobantes',
                        'data' => [round($document_total_payment,2), round($document_total_to_pay,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ]
        ];
    }

    private function document_pos_totals($establishment_id, $date_start, $date_end, $currency_id)
    {

        if($date_start && $date_end){
            $documents = DocumentPos::query()->where('establishment_id', $establishment_id)
                                            ->whereCurrency($currency_id)
                                            ->whereBetween('date_of_issue', [$date_start, $date_end])->get();
        }else{
            $documents = DocumentPos::query()->where('establishment_id', $establishment_id)
                                            ->whereCurrency($currency_id)
                                            ->get();
        }

        //PEN
        $document_total_pen = 0;
        $document_total_payment_pen = 0;
        $document_total_note_credit_pen = 0;

        $document_total_pen = collect($documents)->sum('total');


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
        $document_total_to_pay = $document_total - $document_total_payment;


        return [
            'totals' => [
                'total_payment' => number_format($document_total_payment,2, ".", ""),
                'total_to_pay' => number_format($document_total_to_pay,2, ".", ""),
                'total' => number_format($document_total,2, ".", ""),
            ],
            'graph' => [
                'labels' => ['Total pagado', 'Total por pagar'],
                'datasets' => [
                    [
                        'label' => 'Comprobantes',
                        'data' => [round($document_total_payment,2), round($document_total_to_pay,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ]
        ];
    }

    /**
     * @param $establishment_id
     * @param $date_start
     * @param $date_end
     * @param $period
     * @param $month_start
     * @param $month_end
     * @return array
     */
    private function totals($establishment_id, $date_start, $date_end, $period, $month_start, $month_end, $currency_id)
    {

        if($date_start && $date_end){

            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                            ->whereNotChanged()
                                            ->whereStateTypeAccepted()
                                            ->whereCurrency($currency_id)
                                            ->whereBetween('date_of_issue', [$date_start, $date_end])->get();

            $documents = Document::query()->where('establishment_id', $establishment_id)
                                            ->whereBetween('date_of_issue', [$date_start, $date_end])
                                            ->whereStateTypeAccepted()
                                            ->whereCurrency($currency_id)
                                            ->get();

        }else{

            $sale_notes = SaleNote::query()->where('establishment_id', $establishment_id)
                                            ->whereNotChanged()
                                            ->whereStateTypeAccepted()
                                            ->whereCurrency($currency_id)
                                            ->get();

            $documents = Document::query()->where('establishment_id', $establishment_id)
                                            ->whereStateTypeAccepted()
                                            ->whereCurrency($currency_id)
                                            ->get();

        }

        //DOCUMENT
        $document_total_pen = 0;
        $document_total_note_credit_pen = 0;

        $document_total_pen =  $documents->whereIn('type_document_id', [1, 2])->sum('total');

        foreach ($documents as $document)
        {
            $document_total_note_credit_pen += ($document->document_type_id == '07') ? $document->total:0;
        }

        $document_total = $document_total_pen;
        $document_total_note_credit = $document_total_note_credit_pen;

        $documents_total = $document_total - $document_total_note_credit;

        //DOCUMENT

        //SALE NOTE

        //PEN
        $sale_note_total_pen = 0;
        $sale_note_total_pen = collect($sale_notes)->sum('total');

        //TOTALS
        $sale_notes_total = $sale_note_total_pen;


        $total = $sale_notes_total + $documents_total;


        if(in_array($period, ['month', 'between_months'])) {
            if($month_start === $month_end) {
                $data_array = $this->getDocumentsByDays($sale_notes, $documents, $date_start, $date_end);
            } else {
                $data_array = $this->getDocumentsByMonths($sale_notes, $documents, $month_start, $month_end);
            }
        } else {
            if($date_start === $date_end) {
                $data_array = $this->getDocumentsByHours($sale_notes, $documents);
            } else {
                $data_array = $this->getDocumentsByDays($sale_notes, $documents, $date_start, $date_end);
            }
        }

        return [
            'totals' => [
                'total_documents' => number_format($documents_total,2, ".", ""),
                'total_sale_notes' => number_format($sale_notes_total,2, ".", ""),
                'total' => number_format($total,2, ".", ""),
            ],
            'graph' => [
                'labels' => array_keys($data_array['total_array']),
                'datasets' => [
                    [
                        'label' => 'Total notas de venta',
                        'data' => array_values($data_array['sale_notes_array']),
                        'backgroundColor' => 'rgb(255, 99, 132)',
                        'borderColor' => 'rgb(255, 99, 132)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                    [
                        'label' => 'Total comprobantes',
                        'data' => array_values($data_array['documents_array']),
                        'backgroundColor' => 'rgb(54, 162, 235)',
                        'borderColor' => 'rgb(54, 162, 235)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                    [
                        'label' => 'Total',
                        'data' => array_values($data_array['total_array']),
                        'backgroundColor' => 'rgb(201, 203, 207)',
                        'borderColor' => 'rgb(201, 203, 207)',
                        'borderWidth' => 1,
                        'fill' => false,
                        'lineTension' => 0,
                    ]

                ],
            ]
        ];
    }


    private function getDocumentsByHours($sale_notes, $documents)
    {
        $sale_notes_array = [];
        $documents_array = [];
        $total_array = [];
        $document_total = 0;
        $document_total_note_credit = 0;

        $h_start = 0;
        $h_end = 23;

        for ($h = $h_start; $h <= $h_end; $h++)
        {
            $h_format = str_pad($h, 2, '0', STR_PAD_LEFT);

            //SALE NOTE
            $sale_note_total_pen = $sale_notes->filter(function ($row) use($h_format) {
                                        return substr($row->time_of_issue, 0, 2) === $h_format;
                                    })
                                    //->where('currency_type_id', 'PEN')
                                    ->sum('total');

            $sale_note_total = $sale_note_total_pen;
            $sale_notes_array[$h_format.'h'] = round($sale_note_total, 2);
            //SALE NOTE


            //DOCUMENT
            $document_total_pen = $documents->filter(function ($row) use($h_format) {
                                        return substr($row->time_of_issue, 0, 2) === $h_format;
                                    })
                                    // ->where('currency_type_id', 'PEN')
                                    ->whereIn('type_document_id', [1, 2])
                                    ->sum('total');


            //NC
            $document_total_note_credit_pen = $documents->filter(function ($row) use($h_format) {
                                                    return substr($row->time_of_issue, 0, 2) === $h_format;
                                                })
                                                ->where('type_document_id', 3)
                                                //->where('currency_type_id', 'PEN')
                                                ->sum('total');

            $d_total = $document_total_pen;
            $d_total_nc = $document_total_note_credit_pen;

            $document_total = $d_total - $d_total_nc;

            //DOCUMENT

            $documents_array[$h_format.'h'] = round($document_total, 2);

            $total_array[$h_format.'h'] = round($sale_note_total + $document_total,2);

        }

        return compact('sale_notes_array', 'documents_array', 'total_array');
    }

    private function getDocumentsByDays($sale_notes, $documents, $date_start, $date_end)
    {
        $sale_notes_array = [];
        $documents_array = [];
        $total_array = [];
        $document_total = 0;
        $document_total_note_credit = 0;

        $d_start = Carbon::parse($date_start);
        $d_end = Carbon::parse($date_end);

        while ($d_start <= $d_end)
        {

            //SALE NOTE
            $sale_note_total_pen = collect($sale_notes)->where('date_of_issue', $d_start)->sum('total');
            // $sale_note_total_pen = collect($sale_notes->where('currency_type_id', 'PEN'))->where('date_of_issue', $d_start)->sum('total');

            $sale_note_total = round($sale_note_total_pen, 2);
            $sale_notes_array[$d_start->format('d').'d'] = $sale_note_total;


            //DOCUMENT
            $document_total_pen = collect($documents)
                                    // ->where('currency_type_id', 'PEN')
                                    ->whereIn('type_document_id', [1, 2])
                                    ->where('date_of_issue', $d_start)->sum('total');


            $document_total_note_credit_pen = collect($documents)
                                                ->where('type_document_id', 3)
                                                //->where('currency_type_id', 'PEN')
                                                ->where('date_of_issue', $d_start)
                                                ->sum('total');


            $d_total = $document_total_pen;
            $d_total_note_credit = $document_total_note_credit_pen;

            $document_total = round($d_total - $d_total_note_credit,2);

            $documents_array[$d_start->format('d').'d'] = $document_total;

            $total_array[$d_start->format('d').'d'] = round($sale_note_total + $document_total ,2);

            $d_start = $d_start->addDay();

        }

        return compact('sale_notes_array', 'documents_array', 'total_array');
    }

    private function getDocumentsByMonths($sale_notes, $documents, $month_start, $month_end)
    {
        $sale_notes_array = [];
        $documents_array = [];
        $total_array = [];
        $document_total = 0;
        $document_total_note_credit = 0;

        $m_start = (int) substr($month_start, 5, 2);
        $m_end = (int) substr($month_end, 5, 2);

        for ($m = $m_start; $m <= $m_end; $m++)
        {
            $m_format = str_pad($m, 2, '0', STR_PAD_LEFT);

            //SALE NOTE
            $sale_note_total_pen = 0;
            $sale_note_total_usd = 0;

            // $sale_note_total_pen = $sale_notes->where('currency_type_id', 'PEN')->filter(function ($row) use($m_format) {

            $sale_note_total_pen = $sale_notes->filter(function ($row) use($m_format) {
                                        return $row->date_of_issue->format('m') === $m_format;
                                    })->sum('total');


            $sale_note_total = round($sale_note_total_pen + $sale_note_total_usd, 2);

            $sale_notes_array[$m_format.'m'] = $sale_note_total;


            //DOCUMENT
            $document_total_pen = $documents->filter(function ($row) use($m_format) {
                                                return $row->date_of_issue->format('m') === $m_format;
                                            })
                                            // ->where('currency_type_id', 'PEN')
                                            ->whereIn('type_document_id', [1, 2])
                                            ->sum('total');


            //NC
            $document_total_note_credit_pen = $documents->filter(function ($row) use($m_format) {
                                                    return $row->date_of_issue->format('m') === $m_format;
                                                })
                                                ->where('type_document_id', 3)
                                                // ->where('currency_type_id', 'PEN')
                                                ->sum('total');


            $d_total = $document_total_pen;
            $d_total_nc = $document_total_note_credit_pen;

            $document_total = $d_total - $d_total_nc;
            //DOCUMENT

            $documents_array[$m_format.'m'] = round($document_total, 2);

            $total_array[$m_format.'m'] = round($sale_note_total + $document_total, 2);
        }

        return compact('sale_notes_array', 'documents_array', 'total_array');
    }





    private function balance($establishment_id, $date_start, $date_end, $currency_id){

        $document = $this->get_document_totals($establishment_id, $date_start, $date_end, $currency_id);
        $sale_note = $this->get_sale_note_totals($establishment_id, $date_start, $date_end, $currency_id);
        $purchase = $this->get_purchase_totals($establishment_id, $date_start, $date_end, $currency_id);
        $expense = $this->get_expense_totals($establishment_id, $date_start, $date_end, $currency_id);

        $response_totals_document = $document['totals'];
        $response_totals_sale_note = $sale_note['totals'];
        $response_totals_purchase = $purchase['totals'];
        $response_totals_expense = $expense['totals'];

        // dd($response_totals_document, $response_totals_sale_note, $response_totals_purchase, $response_totals_expense);

        $total_document =  $response_totals_document['total'];
        $total_payment_document =  $response_totals_document['total_payment'];

        $total_sale_note =  $response_totals_sale_note['total'];
        $total_payment_sale_note =  $response_totals_sale_note['total_payment'];

        $total_purchase = $response_totals_purchase['total'];
        $total_payment_purchase = $response_totals_purchase['total_payment'];

        $total_expense = $response_totals_expense['total'];
        $total_payment_expense = $response_totals_expense['total_payment'];

        $all_totals = $total_document + $total_sale_note - $total_expense - $total_purchase;
        $all_totals_payment = $total_payment_document + $total_payment_sale_note - $total_payment_purchase - $total_payment_expense ;

        return [
            'totals' => [
                'total_document' => number_format($total_document,2),
                'total_payment_document' => number_format($total_payment_document,2),
                'total_sale_note' => number_format($total_sale_note,2),
                'total_payment_sale_note' => number_format($total_payment_sale_note,2),
                'total_purchase' => number_format($total_purchase,2),
                'total_payment_purchase' => number_format($total_payment_purchase,2),
                'total_expense' => number_format($total_expense,2),
                'total_payment_expense' => number_format($total_payment_expense,2),

                'all_totals' => number_format($all_totals,2),
                'all_totals_payment' => number_format($all_totals_payment,2),
            ],
            'graph' => [
                'labels' => ['Totales', 'Total pagos'],
                'datasets' => [
                    [
                        'label' => 'Grafico',
                        'data' => [round($all_totals,2), round($all_totals_payment,2)],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                        ]
                    ]
                ],
            ]
        ];
    }

    public function getItems(){

        $items = Item::orderBy('name')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => ($row->internal_id) ? "{$row->internal_id} - {$row->name}" :$row->name,
            ];
        });

        return $items;

    }

}
