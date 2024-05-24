<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>FECHA DE EMISIÓN</th>
            <th>CLIENTE</th>
            <th>DOCUMENTO</th>
            <th>BASE</th>
            <th>DESCUENTO</th>
            @foreach ($taxTitles as $taxTitle)
                <th>{{$taxTitle->name}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($documents as $document)
            <tr>
                <td>{{$document->id}}</td>
                <td>{{$document->date_issue}}</td>
                <td>{{$document->client->name}}</td>
                <td>
                    <div>{{$document->type_document->name}}</div>
                    <div>{{$document->prefix}}{{$document->number}}
                        @if ($document->type_document_id != 1)
                            ({{$document->reference->prefix}}{{$document->reference->number}})
                        @endif
                    </div>
                </td>
                <td>{{number_format($document->sale, 2, '.', ',')}}</td>
                <td>{{number_format($document->total_discount, 2, '.', ',')}}</td>
                @foreach ($taxTitles as $taxe)
                    <td>
                        @if ($exist = collect($document->taxes)->where('id', $taxe->id)->first())
                            {{number_format((($exist->is_retention) ? $exist->retention : $exist->total), 2, '.', ',')}}
                        @else
                            {{number_format(0, 2, '.', ',')}}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
