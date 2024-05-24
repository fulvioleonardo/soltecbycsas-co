@php
    
    $document_type_id = $filters->document_type_id ?? null;
    $brand_id = $filters->brand_id ?? null;
    $item_id = $filters->item_id ?? null;
    $customer_id = $filters->customer_id ?? null;
    $user_id = $filters->user_id ?? null;
    $start_date = $filters->start_date ?? null;
    $end_date = $filters->end_date ?? null;
    $start_time = $filters->start_time ?? null;
    $end_time = $filters->end_time ?? null;

@endphp

@inject('view_filter_service', 'App\Services\ViewFiltersService')

<div style="margin-top:10px; margin-bottom:20px;">
    <table>
        <tr>
            <td>Desde: {{$start_date}} {{$start_time}}</td>
            <td>Hasta: {{$end_date}} {{$end_time}}</td>
        </tr>
        <tr>
            <td>Tipo documento: {{ $view_filter_service->getTypeName($document_type_id) }}</td>
            <td>Cliente: {{ $customer_id ? $view_filter_service->getCustomerName($customer_id) : 'Todos' }}</td>
        </tr>
        <tr>
            <td>Vendedor: {{ $user_id ? $view_filter_service->getUserName($user_id) : 'Todos' }}</td>
            <td>Producto: {{ $item_id ? $view_filter_service->getItemName($item_id) : 'Todos' }}</td>
        </tr>
        <tr>
            <td>Marca: {{ $brand_id ? $view_filter_service->getBrandName($brand_id) : 'Todos' }}</td>
        </tr>
    </table>
</div>