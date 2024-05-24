
@php
    use Modules\Factcolombia1\Helpers\DocumentHelper;
@endphp
<table class="">
    <thead>
        <tr>
            <th colspan="5"></th>
            
            @foreach($taxes as $tax)
                <th colspan="2">
                    IMPUESTO #{{ $loop->iteration }}
                    <br>
                    {{ $tax->name }} - ({{ $tax->rate }}%)
                </th>
            @endforeach
        </tr>
        <tr>
            <th>Fact. Inicial</th>
            <th>Fact. Final</th>

            <th>Total/Neto</th>
            <th>Total <br>+<br> Impuesto</th>
            <th>Total/Excento</th>
            
            @foreach($taxes as $tax)
                <th>Base</th>
                <th>Impuesto</th>
            @endforeach
        </tr>
    </thead>
    <tbody>

        @php
            $global_total_exempt = 0;
        @endphp

        @foreach($summary_records as $record)

            @php
                $net_total = 0;
                $total = 0;
                $total_exempt = 0;
                $first_document = $record['first_document'];
                $ordered_documents = $record['ordered_documents'];
                $row_first_document = $first_document->getDataReportSalesBook();

                foreach ($ordered_documents as $document)
                {
                    $totals = $document->getDataReportSummarySalesBook();
                    $net_total += $totals['net_total'];
                    $total += $totals['total'];
                    $total_exempt += $totals['total_exempt'];
                }

                $global_total_exempt += $total_exempt;

            @endphp

            <tr>
                <td class="celda">{{$row_first_document['type_document_name']}} <br/> {{ $record['prefix'] }}-{{$record['first_document']->number}}</td>
                <td class="celda">{{$row_first_document['type_document_name']}} <br/> {{ $record['prefix'] }}-{{$record['last_document']->number}}</td>

                {{-- TOTALES --}}
                <td class="celda text-right-td">{{ $first_document->generalApplyNumberFormat($net_total) }}</td>
                <td class="celda text-right-td">{{ $first_document->generalApplyNumberFormat($total) }}</td>
                <td class="celda text-right-td"> {{ $first_document->generalApplyNumberFormat($total_exempt) }} </td>
                {{-- TOTALES --}}
                
                {{-- IMPUESTOS --}}
                @foreach($taxes as &$tax)

                    @php
                        $sum_taxable_amount = 0;
                        $sum_tax_amount = 0;

                        foreach ($ordered_documents as $document)
                        {
                            $item_values = $document->getItemValuesByTax($tax->id);
                            $sum_taxable_amount += $item_values['taxable_amount'];
                            $sum_tax_amount += $item_values['tax_amount'];
                        }

                        $tax->global_taxable_amount += $sum_taxable_amount;
                        $tax->global_tax_amount += $sum_tax_amount;
                    @endphp
                    
                    <td class="celda text-right-td">{{ $first_document->generalApplyNumberFormat($sum_taxable_amount) }}</td>
                    <td class="celda text-right-td">{{ $first_document->generalApplyNumberFormat($sum_tax_amount) }}</td>

                @endforeach
                {{-- IMPUESTOS --}}

            </tr>
        @endforeach
    </tbody>
</table>

<br><br>

<table style="width: 60% !important">
    <thead>
        <tr>
            <th></th>
            <th>BASE</th>
            <th>IMPUESTO</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="celda">TOTAL VENTAS EXENTAS</td>
            <td class="celda text-right-td">{{ DocumentHelper::applyNumberFormat($global_total_exempt) }}</td>
            <td class="celda text-right-td">0.00</td>
        </tr>

        @foreach($taxes as $tax)
            <tr>
                <td class="celda">
                    TOTAL VENTAS IMPUESTO #{{ $loop->iteration }}
                    {{-- <br> --}}
                    - {{ $tax->name }} ({{ $tax->rate }}%)
                </td>
                <td class="celda text-right-td">{{ DocumentHelper::applyNumberFormat($tax->global_taxable_amount) }}</td>
                <td class="celda text-right-td">{{ DocumentHelper::applyNumberFormat($tax->global_tax_amount) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>