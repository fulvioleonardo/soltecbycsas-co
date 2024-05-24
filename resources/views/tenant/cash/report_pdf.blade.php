@php

    $establishment = $cash->user->establishment;
    $final_balance = 0;
    $cash_income = 0;
    // Calcular el total de egresos (gastos)
    $cashEgress = $cash->cash_documents->sum(function ($cashDocument) {
        return $cashDocument->expense_payment ? $cashDocument->expense_payment->payment : 0;
    });
    $cash_final_balance = 0;
    $document_count = 0;
    $cash_taxes = 0;
    $cash_documents = $cash->cash_documents;
    $is_complete = $only_head === 'resumido' ? false : true;
    $first_document = '';
    $last_document = '';

    $list = $cash_documents->filter(function ($item) {
        return $item->document_pos_id !== null;
    });
    if ($list->count() > 0) {
        $first_document = $list->first()->document_pos->series . '-' . $list->first()->document_pos->number;
        $last_document = $list->last()->document_pos->series . '-' . $list->last()->document_pos->number;
    }

    foreach ($methods_payment as $method) {
        $method->transaction_count = 0; // Se Incializa el contador de transacciones
    }

    foreach ($cash_documents as $cash_document) {
        if ($cash_document->document_pos) {
            $cash_income += $cash_document->document_pos->getTotalCash();
            $final_balance += $cash_document->document_pos->getTotalCash();
            $cash_taxes += $cash_document->document_pos->total_tax;
            $document_count = $cash_document->document_pos->count();

            if (count($cash_document->document_pos->payments) > 0) {
                $pays =
                    $cash_document->document_pos->state_type_id === '11'
                        ? collect()
                        : $cash_document->document_pos->payments;
                foreach ($methods_payment as $record) {
                    $record->sum = $record->sum + $pays->where('payment_method_type_id', $record->id)->sum('payment');
                }

                foreach ($cash_document->document_pos->payments as $payment) {
                    $paymentMethod = $methods_payment->firstWhere('id', $payment->payment_method_type_id);
                    if ($paymentMethod) {
                        $paymentMethod->transaction_count++; // Se incrementa el contador de transacciones
                    }
                }
            }
        }
    }
    $cash_final_balance = $final_balance + $cash->beginning_balance - $cashEgress;

@endphp

<<!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte POS - {{ $cash->user->name }} - {{ $cash->date_opening }} {{ $cash->time_opening }}</title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-spacing: 0;
                border: 1px solid black;
            }

            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }

            th {
                padding: 5px;
                text-align: center;
                border-color: #0088cc;
                border: 0.1px solid black;
            }

            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 20px !important;
                text-decoration: underline;
            }

            p>strong {
                margin-left: 5px;
                font-size: 12px;
            }

            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }

            tbody {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .td-custom {
                line-height: 0.1em;
            }

            .totales {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: right;
            }

            html {
                font-family: sans-serif;
                font-size: 8px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid black;
            }

            th,
            td {
                padding: 2px;
                border: 1px solid black;
                text-align: center;
                font-size: 8px;
            }

            th {
                background-color: #0088cc;
                color: white;
                font-weight: bold;
            }

            .title {
                font-weight: bold;
                text-align: center;
                font-size: 16px;
                text-decoration: underline;
            }

            p,
            p>strong {
                font-size: 8px;
            }

            .totales {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: right;
            }

            /* Estilos encabezado */
            html {
                font-family: sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid black;
            }

            th,
            .celda {
                padding: 5px;
                border: 1px solid black;
                text-align: center;
            }

            th {
                background-color: #0088cc;
                color: white;
                font-weight: bold;
            }

            .title {
                font-weight: bold;
                text-align: center;
                font-size: 20px;
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div>
            <p align="center" class="title"><strong>COMPROBANTE INFORME DIARIO</strong></p>
            <div style="margin-top: -30px;" class="text-center">
                <p>
                    <strong>Empresa: </strong>{{ $company->name }} <br>
                    <strong>N° Documento: </strong>{{ $company->number }} <br>
                    <strong>Establecimiento: </strong>{{ $establishment->description }} <br>
                    <strong>Fecha reporte: </strong>{{ date('Y-m-d') }} <br>
                    <strong>Vendedor:</strong> {{ $cash->user->name }}
                    <strong>Fecha y hora apertura:</strong> {{ $cash->date_opening }} {{ $cash->time_opening }}
                    <strong>Estado de caja:</strong> {{ $cash->state ? 'Aperturada' : 'Cerrada' }}
                    @if (!$cash->state)
                        <br>
                        <strong>Fecha y hora cierre:</strong> {{ $cash->date_closed }} {{ $cash->time_closed }}
                    @endif
                </p>
            </div>
        </div>
        @php
            $is_complete = $only_head === 'resumido' ? false : true;
        @endphp
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Reporte POS - {{ $cash->user->name }} - {{ $cash->date_opening }} {{ $cash->time_opening }}
            </title>
        </head>

        <body>
            <div>
                @php
                    $tipoComprobante = 'Factura POS';
                    $numeroInicial = null;
                    $numeroFinal = null;

                    foreach ($cash_documents as $cash_document) {
                        if ($cash_document->document_pos) {
                            $numeroActual = $cash_document->document_pos->number_full;
                            if (!$numeroInicial || $numeroActual < $numeroInicial) {
                                $numeroInicial = $numeroActual;
                            }
                            if (!$numeroFinal || $numeroActual > $numeroFinal) {
                                $numeroFinal = $numeroActual;
                            }
                        }
                    }
                @endphp

                <table style="width: 50%;" class="right-half-table">
                    <tr>
                        <th class="celda">Tipo de comprobante</th>
                        <th class="celda">Comprobante Número inicial</th>
                        <th class="celda">Número final</th>
                    </tr>
                    <tr>
                        <td class="celda">{{ $tipoComprobante }}</td>
                        <td class="celda">{{ $numeroInicial }}</td>
                        <td class="celda">{{ $numeroFinal }}</td>
                    </tr>
                </table>

                <table style="width: 70%;">
                    <tr>
                        <td><strong>Empresa:</strong> {{ $company->name }}</td>
                        <td><strong>Fecha reporte:</strong> {{ date('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Establecimiento:</strong> {{ $establishment->description }}</td>
                        <td><strong>Vendedor:</strong> {{ $cash->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Fecha y hora apertura:</strong> {{ $cash->date_opening }}
                            {{ $cash->time_opening }}</td>
                        <td><strong>Estado de caja:</strong> {{ $cash->state ? 'Aperturada' : 'Cerrada' }}</td>
                    </tr>
                    @if (!$cash->state)
                        <tr>
                            <td colspan="2"><strong>Fecha y hora cierre:</strong> {{ $cash->date_closed }}
                                {{ $cash->time_closed }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            @if (!$is_complete)
                <!-- Informe Resumido -->
                <table>
                    <tr>
                        <th>Saldo inicial</th>
                        <th>Ingreso</th>
                        <th>Egreso</th>
                        <th>Saldo final</th>
                    </tr>
                    <tr>
                        <td>${{ number_format($cash->beginning_balance, 2, '.', ',') }}</td>
                        <td>${{ number_format($cash_income, 2, '.', ',') }}</td>
                        <td>${{ number_format($cashEgress, 2, '.', ',') }}</td>
                        <td>${{ number_format($cash->beginning_balance + $cash_income - $cashEgress, 2, '.', ',') }}
                        </td>
                    </tr>
                </table>

                @if ($cash_documents->count())
                    <div>
                        <h3>Totales por medio de pago</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Medio de Pago</th>
                                    <th>Número de Transacciones</th>
                                    <th>Valor Transacción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSum = 0; @endphp
                                @php $totalTransactions = 0; @endphp
                                @foreach ($methods_payment as $item)
                                    @php
                                        $totalSum += $item->sum;
                                        $totalTransactions += $item->transaction_count;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->transaction_count }}</td>
                                        <td>${{ number_format($item->sum, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($totalSum, 2, '.', ',') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No se encontraron registros de documentos.</p>
                @endif
            @else
                <!-- Informe Completo -->
                <table>
                    <tr>
                        <th>Saldo inicial</th>
                        <th>Ingreso</th>
                        <th>Egreso</th>
                        <th>Saldo final</th>
                    </tr>
                    <tr>
                        <td>${{ number_format($cash->beginning_balance, 2, '.', ',') }}</td>
                        <td>${{ number_format($cash_income, 2, '.', ',') }}</td>
                        <td>${{ number_format($cashEgress, 2, '.', ',') }}</td>
                        <td>${{ number_format($cash->beginning_balance + $cash_income - $cashEgress, 2, '.', ',') }}
                        </td>
                    </tr>
                </table>

                @if ($cash_documents->count())
                    <div>
                        <h3>Totales por medio de pago</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Medio de Pago</th>
                                    <th>Número de Transacciones</th>
                                    <th>Valor Transacción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSum = 0; @endphp
                                @php $totalTransactions = 0; @endphp
                                @foreach ($methods_payment as $item)
                                    @php
                                        $totalSum += $item->sum;
                                        $totalTransactions += $item->transaction_count;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->transaction_count }}</td>
                                        <td>${{ number_format($item->sum, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($totalSum, 2, '.', ',') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No se encontraron registros de documentos.</p>
                @endif

                @php
                    $all_documents = [];
                    $totalsByCategory = [];
                    $ivaTotalsByCategory = []; // Para almacenar los totales de IVA por categoría

                    foreach ($cash_documents as $cash_document) {
                        if ($cash_document->document_pos) {
                            $all_documents[] = $cash_document;
                        }
                    }

                    foreach ($all_documents as $document) {
                        foreach ($document->document_pos->items as $item) {
                            if ($item->refund == 0) {
                                $categoryId = $item->item->category_id ?? 'Categoría no especificada';

                                if (!isset($totalsByCategory[$categoryId])) {
                                    $totalsByCategory[$categoryId] = [
                                        'subtotal' => 0,
                                        'discount' => 0,
                                        'otherTaxes' => 0,
                                        'iva' => [],
                                        'total' => 0,
                                    ];
                                }

                                $itemIvaRate = $item->tax->name; // "IVA19", "IVA5", etc.
                                $itemIvaValue = $item->total_tax; // Total de IVA para ese ítem

                                if (!isset($totalsByCategory[$categoryId]['iva'][$itemIvaRate])) {
                                    $totalsByCategory[$categoryId]['iva'][$itemIvaRate] = 0;
                                }

                                // Acumular el IVA por tasa dentro de cada categoría
                                $totalsByCategory[$categoryId]['iva'][$itemIvaRate] += $itemIvaValue;

                                // Sumar los otros valores al total por categoría
                                $totalsByCategory[$categoryId]['subtotal'] += $item->subtotal ?? 0;
                                $totalsByCategory[$categoryId]['discount'] += $item->discount ?? 0;
                                $totalsByCategory[$categoryId]['otherTaxes'] += $item->other_taxes ?? 0;
                                $totalsByCategory[$categoryId]['total'] += $item->total ?? 0;
                            }
                        }
                    }
                    // Ahora, se calcula el total de IVA por categoría
                    foreach ($totalsByCategory as $categoryId => &$categoryTotals) {
                        $categoryTotals['totalIva'] = array_sum($categoryTotals['iva']); // Se Suma todos los valores de IVA
                    }
                    unset($categoryTotals); // Se Quita referencia
                @endphp
                <br><br>
                <h3 style="margin-top: -10px;"> Relación de Gastos</h3>

                @if ($expensePayments->isNotEmpty())
                    <table style="margin-top: -10px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Referencia</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expensePayments as $index => $expensePayment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $expensePayment->reference ?? 'Sin referencia' }}</td>
                                    <td>{{ number_format($expensePayment->payment, 2) }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @else
                    <p>No se encontraron gastos.</p>
                @endif

                <h3> Totales por Categorías</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Categoría/IVA</th>
                            <th>Tarifa</th>
                            <th>Base Gravable</th>
                            <th>Descuento</th>
                            <th>Valor IVA</th>
                            <th>Valor neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotalSubtotal = 0;
                            $grandTotalDiscount = 0;
                            $grandTotalOtherTaxes = 0;
                            $grandTotalIva = 0;
                            $grandTotal = 0;
                        @endphp
                        @foreach ($totalsByCategory as $categoryId => $totals)
                            @php
                                // Se Suma los totales de cada columna para el gran total
                                $grandTotalSubtotal += $totals['subtotal'] - $totals['totalIva'];
                                $grandTotalDiscount += $totals['discount'];
                                $grandTotalIva += $totals['totalIva'];
                                $grandTotal += $totals['total'];
                            @endphp
                            @php
                                $categoryName = $categories[$categoryId] ?? 'Categoría no especificada';
                            @endphp
                            <tr>
                                <td style="text-align: left;">{{ $categoryName }}</td>
                                <td>
                                    @foreach ($totals['iva'] as $ivaName => $ivaValue)
                                        @if ($ivaValue)
                                            {{ $ivaName }}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ number_format($totals['subtotal'] - $totals['totalIva'], 2) }}</td>
                                <td>{{ number_format($totals['discount'], 2) }}</td>
                                <td>{{ number_format($totals['totalIva'], 2) }}</td>
                                <td>{{ number_format($totals['total'], 2) }}</td>
                            </tr>
                        @endforeach
                        <!-- Fila para mostrar los totales -->
                        <tr class="totales">
                            <td><strong>Totales:</strong></td>
                            <td></td>
                            <td>{{ number_format($grandTotalSubtotal, 2) }}</td>
                            <td>{{ number_format($grandTotalDiscount, 2) }}</td>
                            <td>{{ number_format($grandTotalIva, 2) }}</td>
                            <td>{{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Inicio de codigo devoluciones en ventas -->
                @php
                    $all_documents = [];
                    $totalsByCategory = [];
                    $ivaTotalsByCategory = []; //Almacenar los totales de IVA por categoría

                    foreach ($cash_documents as $cash_document) {
                        if ($cash_document->document_pos) {
                            $all_documents[] = $cash_document;
                        }
                    }

                    foreach ($all_documents as $document) {
                        foreach ($document->document_pos->items as $item) {
                            // Filtrar solo los items que son reembolsos (refund == 1)
                            if ($item->refund == 1) {
                                $categoryId = $item->item->category_id ?? 'Categoría no especificada';

                                if (!isset($totalsByCategory[$categoryId])) {
                                    $totalsByCategory[$categoryId] = [
                                        'subtotal' => 0,
                                        'discount' => 0,
                                        'otherTaxes' => 0,
                                        'iva' => [],
                                        'total' => 0,
                                    ];
                                }

                                $itemIvaRate = $item->tax->name; // "IVA19", "IVA5", etc.
                                $itemIvaValue = $item->total_tax; // Total de IVA para ese ítem

                                if (!isset($totalsByCategory[$categoryId]['iva'][$itemIvaRate])) {
                                    $totalsByCategory[$categoryId]['iva'][$itemIvaRate] = 0;
                                }

                                // Se Acumula el IVA por tasa dentro de cada categoría
                                $totalsByCategory[$categoryId]['iva'][$itemIvaRate] += $itemIvaValue;

                                // Se Suman los otros valores al total por categoría
                                $totalsByCategory[$categoryId]['subtotal'] += $item->subtotal ?? 0;
                                $totalsByCategory[$categoryId]['discount'] += $item->discount ?? 0;
                                $totalsByCategory[$categoryId]['otherTaxes'] += $item->other_taxes ?? 0;
                                $totalsByCategory[$categoryId]['total'] += $item->total ?? 0;
                            }
                        }
                    }

                    // Ahora, calcular el total de IVA por categoría
                    foreach ($totalsByCategory as $categoryId => &$categoryTotals) {
                        $categoryTotals['totalIva'] = array_sum($categoryTotals['iva']); // Sumar todos los valores de IVA
                    }
                    unset($categoryTotals); // Quitar referencia

                @endphp

                <h3> Totales por Categorías devoluciones en ventas</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Categoría/IVA</th>
                            <th>Tarifa</th>
                            <th>Base Gravable</th>
                            <th>Descuento</th>
                            <th>Valor IVA</th>
                            <th>Valor neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotalSubtotal = 0;
                            $grandTotalDiscount = 0;
                            $grandTotalOtherTaxes = 0;
                            $grandTotalIva = 0;
                            $grandTotal = 0;
                        @endphp
                        @foreach ($totalsByCategory as $categoryId => $totals)
                            @php
                                $grandTotalSubtotal += $totals['subtotal'] - $totals['totalIva'];
                                $grandTotalDiscount += $totals['discount'];
                                $grandTotalIva += $totals['totalIva'];
                                $grandTotal += $totals['total'];
                            @endphp
                            @php
                                $categoryName = $categories[$categoryId] ?? 'Categoría no especificada';
                            @endphp
                            <tr>
                                <td style="text-align: left;">{{ $categoryName }}</td>
                                <td>
                                    @foreach ($totals['iva'] as $ivaName => $ivaValue)
                                        @if ($ivaValue)
                                            {{ $ivaName }}<br>
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ number_format($totals['subtotal'] - $totals['totalIva'], 2) }}</td>
                                <td>{{ number_format($totals['discount'], 2) }}</td>
                                <td>{{ number_format($totals['totalIva'], 2) }}</td>
                                <td>{{ number_format($totals['total'], 2) }}</td>
                            </tr>
                        @endforeach
                        <!-- Fila para mostrar los totales -->
                        <tr class="totales">
                            <td><strong>Totales:</strong></td>
                            <td></td>
                            <td>{{ number_format($grandTotalSubtotal, 2) }}</td>
                            <td>{{ number_format($grandTotalDiscount, 2) }}</td>
                            <td>{{ number_format($grandTotalIva, 2) }}</td>
                            <td>{{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                @php
                    $all_documents = [];
                    $totalsByIvaRate = [];
                    $totalValorAntesIva = 0;
                    $totalValorIva = 0;
                    $totalGeneral = 0;

                    foreach ($cash_documents as $cash_document) {
                        if ($cash_document->document_pos) {
                            foreach ($cash_document->document_pos->items as $item) {
                                $ivaName = $item->tax->name;
                                $ivaRate = $item->tax->rate;
                                $ivaTotal = $item->total_tax;
                                $subtotal = $item->subtotal ?? 0;

                                if (!isset($totalsByIvaRate[$ivaName])) {
                                    $totalsByIvaRate[$ivaName] = [
                                        'rate' => $ivaRate,
                                        'base_gravable' => 0,
                                        'valor_iva' => 0,
                                        'total' => 0,
                                    ];
                                }
                                $subtotalSinIva = $subtotal / (1 + $ivaRate / 100);
                                $ivaCalculado = $subtotalSinIva * ($ivaRate / 100);
                                // Acumular los subtotales y el IVA por cada tarifa
                                $totalsByIvaRate[$ivaName]['base_gravable'] += $subtotalSinIva;
                                $totalsByIvaRate[$ivaName]['valor_iva'] += $ivaCalculado;
                                $totalsByIvaRate[$ivaName]['total'] += $subtotal; // Total ya incluye IVA

                                // Acumular totales
                                $totalValorAntesIva += $subtotalSinIva;
                                $totalValorIva += $ivaCalculado;
                                $totalGeneral += $subtotal; // Total ya incluye IVA
                            }
                        }
                    }
                @endphp

                <h3>Totales por tarifa de IVA</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tarifa</th>
                            <th>Base Gravable</th>
                            <th>Valor IVA</th>
                            <th>Total Incluido IVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($totalsByIvaRate as $ivaName => $totals)
                            <tr>
                                <td>{{ $ivaName }} ({{ $totals['rate'] }}%)</td>
                                <td>{{ number_format($totals['base_gravable'], 2, '.', ',') }}</td>
                                <td>{{ number_format($totals['valor_iva'], 2, '.', ',') }}</td>
                                <td>{{ number_format($totals['total'], 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                        <!-- Fila de totales -->
                        <tr style="font-weight: bold;">
                            <td>Total</td>
                            <td>{{ number_format($totalValorAntesIva, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalValorIva, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalGeneral, 2, '.', ',') }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin: auto; width: 80%;">
                    <h2 style="text-align: center;">Inventario de máquinas</h2>

                    <table border="1" style="width: 100%; margin: auto;">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Tipo de caja</th>
                                <th style="text-align: center;">Número de Caja o Serial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resolutions_maquinas as $resolution)
                                <tr>
                                    <td style="text-align: center;">{{ $resolution->cash_type ?? 'N/A' }}</td>
                                    <td style="text-align: center;">{{ $resolution->plate_number ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="text-align: center;">No se encontraron máquinas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @endif
        </body>

        </html>


    </body>

    </html>
