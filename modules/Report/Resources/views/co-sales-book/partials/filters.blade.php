@php
    
    $document_type_id = $filters->document_type_id ?? null;
    $customer_id = $filters->customer_id ?? null;
    $start_date = $filters->start_date ?? null;
    $end_date = $filters->end_date ?? null;

@endphp

@inject('view_filter_service', 'App\Services\ViewFiltersService')

<div style="margin-top:10px; margin-bottom:20px;">
    <table>
        <tr>
            <td>Desde: {{$start_date}}</td>
            <td>Hasta: {{$end_date}}</td>
        </tr>
        <tr>
            <td>Tipo documento: {{ $view_filter_service->getTypeName($document_type_id) }}</td>
            <td>Cliente: {{ $customer_id ? $view_filter_service->getCustomerName($customer_id) : 'Todos' }}</td>
        </tr>
    </table>
</div>