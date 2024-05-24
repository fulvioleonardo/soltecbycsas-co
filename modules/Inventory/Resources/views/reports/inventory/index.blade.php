@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de inventarios</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('reports.inventory.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            {{-- <div class="el-form-item col-xs-12">
                                <div class="el-form-item__content">
                                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div> --}}
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">

                            <div style="margin-bottom: 10px" class="row">

                                <div style="padding-top: 0.5%" class="col-md-6">
                                    <form action="{{route('reports.inventory.index')}}" method="get">
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select class="form-control" name="warehouse_id" id="">
                                                    <option {{ request()->warehouse_id == 'all' ?  'selected' : ''}} selected value="all">Todos</option>
                                                    @foreach($warehouses as $item)
                                                    <option {{ request()->warehouse_id == $item->id ?  'selected' : ''}} value="{{$item->id}}">{{$item->description}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4"> <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button></div>
                                        </div>
                                    </form>
                                </div>
                                @if(isset($reports))
                                    <div class="col-md-4">
                                        <form action="{{route('reports.inventory.pdf')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                                            <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                            {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                        </form>

                                        <form action="{{route('reports.inventory.report_excel')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="warehouse_id" value="{{request()->warehouse_id ? request()->warehouse_id : 'all'}}">
                                            <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                            {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                        </form>
                                    </div>

                                @endif


                            </div>
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Inventario actual</th>
                                        <th>Precio de venta</th>
                                        <th>Costo</th>
                                        <th>Almacén</th>

                                        <th class="text-right">
                                            Precio de venta Global
                                            <el-tooltip class="item" effect="dark" content="Precio de venta * Inventario actual (Stock)" placement="top-start">
                                                <i class="fa fa-info-circle"></i>
                                            </el-tooltip>
                                        </th>
                                        <th class="text-right">
                                            Costo Global
                                            <el-tooltip class="item" effect="dark" content="Costo * Inventario actual (Stock)" placement="top-start">
                                                <i class="fa fa-info-circle"></i>
                                            </el-tooltip>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    {{-- @php
                                        $total_global_sale_unit_price = 0;
                                        $total_global_purchase_unit_price = 0;
                                    @endphp --}}

                                    @foreach($reports as $key => $value)

                                        @php
                                            $global_sale_unit_price = $value->getGlobalSaleUnitPrice();
                                            $global_purchase_unit_price = $value->getGlobalPurchaseUnitPrice();
                                            
                                            // $total_global_sale_unit_price += $global_sale_unit_price;
                                            // $total_global_purchase_unit_price += $global_purchase_unit_price;
                                        @endphp

                                        <tr>
                                            <td class="celda">{{$loop->iteration}}</td>
                                            <td class="celda">{{$value->item->internal_id ?? ''}} {{$value->item->internal_id ? '-':''}} {{$value->item->name ?? ''}}</td>
                                            <td class="celda">{{$value->stock}}</td>
                                            <td class="celda">{{$value->item->sale_unit_price}}</td>
                                            <td class="celda">{{$value->item->purchase_unit_price}}</td>
                                            <td class="celda">{{$value->warehouse->description}}</td>

                                            <td class="celda text-right">{{$global_sale_unit_price}}</td>
                                            <td class="celda text-right">{{$global_purchase_unit_price}}</td>
                                        </tr>
                                    @endforeach

                                    {{-- <tr>
                                        <td class="celda" colspan="5"></td>
                                        <td class="celda">Total</td>
                                        <td class="celda text-right">{{ number_format($total_global_sale_unit_price, 6, ".", "") }}</td>
                                        <td class="celda text-right">{{ number_format($total_global_purchase_unit_price, 6, ".", "") }}</td>
                                    </tr> --}}

                                </tbody>
                            </table>
                            Total {{$reports->total()}}
                            <label class="pagination-wrapper ml-2">
                                {{$reports->appends($_GET)->render()}}
                            </label>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
