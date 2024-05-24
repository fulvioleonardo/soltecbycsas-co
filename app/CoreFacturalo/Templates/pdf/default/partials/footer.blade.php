@php
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
@endphp
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width">
    <tr>
        <td class="desc font-bold">Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
        <td class="desc font-bold" align="right">Página {PAGENO} de {nb}</td>
    </tr>
</table>
</body>
