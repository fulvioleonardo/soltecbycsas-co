<div>
    <p align="center" class="title"><strong>Reporte Documentos POS</strong></p>
</div>
<div style="margin-top:20px; margin-bottom:20px;">
    <table>
        <tr>
            <td>
                <p><strong>Empresa: </strong>{{$company->name}}</p>
            </td>
            <td>
                <p><strong>Fecha: </strong>{{date('Y-m-d')}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>N° Documento: </strong>{{$company->number}}</p>
            </td>
            <td>
                <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->country->name}} - {{$establishment->department->name}} - {{$establishment->city->name}}</p>
            </td>
        </tr>
    </table>
</div>