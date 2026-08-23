<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Reporte de Ventas
    </title>


    <style>

        {!! file_get_contents(
            public_path('css/pdf.css')
        ) !!}

        {!! file_get_contents(
            public_path('css/pdf_general.css')
        ) !!}

    </style>

</head>


@php

    /*
    |--------------------------------------------------------------------------
    | ESTADOS
    |--------------------------------------------------------------------------
    */

    $statusTranslations = [

        'PAID' =>
            'Pagado',

        'CANCELLED' =>
            'Anulado',

        'PENDING' =>
            'Pendiente'
    ];


    /*
    |--------------------------------------------------------------------------
    | RESUMEN GENERAL
    |--------------------------------------------------------------------------
    |
    | Todos estos valores ya vienen calculados
    | desde ExportController.
    |
    */

    $totalVentas =
        $resumenGeneral[
            'cantidadVentas'
        ] ?? 0;


    $valorBruto =
        $resumenGeneral[
            'valorBruto'
        ] ?? 0;


    $totalDescuento =
        $resumenGeneral[
            'descuento'
        ] ?? 0;


    $subtotal =
        $resumenGeneral[
            'subtotal'
        ] ?? 0;


    $totalImpuestos =
        $resumenGeneral[
            'impuestos'
        ] ?? 0;


    $totalImporte =
        $resumenGeneral[
            'totalVenta'
        ] ?? 0;


    $totalItems =
        $resumenGeneral[
            'totalCantidad'
        ] ?? 0;

@endphp


<body>


{{-- ================================================================ --}}
{{-- ENCABEZADO --}}
{{-- ================================================================ --}}

<table class="header-table">

    <tr>


        {{-- LOGO --}}
        <td class="header-logo">

            @if(!empty(
                $empresa->imagen_pdf
            ))

                <img
                    src="{{ $empresa->imagen_pdf }}"
                    alt="{{ $empresa->name }}"
                    class="invoice-logo"
                >

            @endif

        </td>


        {{-- TÍTULO --}}
        <td class="header-title">

            <div class="report-title">

                @if($reportType == 0)

                    REPORTE DE VENTAS DEL DÍA

                @else

                    REPORTE DE VENTAS POR FECHAS

                @endif

            </div>


            <div class="company-name">

                {{ strtoupper(
                    $empresa->name
                ) }}

            </div>


            <div class="report-subtitle">

                Sistema de control y
                administración de ventas

            </div>

        </td>


        {{-- EMPRESA --}}
        <td class="header-company">

            <div class="company-data">

                <strong>
                    {{ $empresa->name }}
                </strong>

                <br>


                NIT:

                <strong>
                    {{ $empresa->nit ?? 'N/A' }}
                </strong>

                <br>


                Dirección:

                <strong>
                    {{ $empresa->address ?? 'N/A' }}
                </strong>

                <br>


                Email:

                <strong>
                    {{ $empresa->email ?? 'N/A' }}
                </strong>

                <br>


                Teléfono:

                <strong>
                    {{ $empresa->phone ?? 'N/A' }}
                </strong>

            </div>

        </td>

    </tr>

</table>



{{-- ================================================================ --}}
{{-- INFORMACIÓN DE CONSULTA --}}
{{-- ================================================================ --}}

<table class="report-info">

    <tr>


        <td class="label">

            Tipo de reporte:

        </td>


        <td class="value">

            @if($reportType == 0)

                Ventas del día

            @else

                Ventas por rango de fechas

            @endif

        </td>


        <td class="label">

            Generado por:

        </td>


        <td class="value">

            <strong>
                {{ $user }}
            </strong>

        </td>

    </tr>



    <tr>


        <td class="label">

            Período:

        </td>


        <td class="value">

            @if($reportType != 0)

                {{ $dateFrom }}

                al

                {{ $dateTo }}

            @else

                {{ \Carbon\Carbon::now()
                    ->format(
                        'd-m-Y'
                    ) }}

            @endif

        </td>


        <td class="label">

            Fecha generación:

        </td>


        <td class="value">

            {{ \Carbon\Carbon::now()
                ->format(
                    'd-m-Y H:i:s'
                ) }}

        </td>

    </tr>

</table>



{{-- ================================================================ --}}
{{-- TABLA DE VENTAS --}}
{{-- ================================================================ --}}

<table class="sales-table">

    <thead>

    <tr>

        <th width="4%">
            #
        </th>


        <th width="6%">
            Venta
        </th>


        <th width="10%">
            Bruto
        </th>


        <th width="10%">
            Descuento
        </th>


        <th width="10%">
            Total
        </th>


        <th width="7%">
            Cant.
        </th>


        <th width="9%">
            Estado
        </th>


        <th width="14%">
            Cliente / Vendedor
        </th>


        <th width="13%">
            Usuario
        </th>


        <th width="17%">
            Fecha / Hora
        </th>

    </tr>

    </thead>


    <tbody>


    @forelse(
        $ventas
        as $index => $venta
    )

        @php

            /*
             * Sale original.
             */
            $item =
                $venta['sale'];


            /*
             * Resumen de esta venta.
             */
            $resumen =
                $venta['resumen'];


            /*
             * Estado.
             */
            $statusText =
                $statusTranslations[
                    $item->status
                ]
                ?? $item->status;


            $statusClass =
                match($item->status) {

                    'PAID' =>
                        'status-paid',

                    'PENDING' =>
                        'status-pending',

                    'CANCELLED' =>
                        'status-cancelled',

                    default =>
                        ''
                };

        @endphp


        <tr>


            {{-- # --}}
            <td class="text-center">

                {{ $index + 1 }}

            </td>


            {{-- VENTA --}}
            <td class="text-center">

                {{ $item->id }}

            </td>


            {{-- VALOR BRUTO --}}
            <td class="text-right">

                {{ $currency }}.
                {{ number_format(
                    $resumen[
                        'valorBruto'
                    ],
                    2
                ) }}

            </td>


            {{-- DESCUENTO --}}
            <td class="text-right">

                @if(
                    $resumen[
                        'descuento'
                    ] > 0
                )

                    - {{ $currency }}.
                    {{ number_format(
                        $resumen[
                            'descuento'
                        ],
                        2
                    ) }}

                @else

                    {{ $currency }}. 0.00

                @endif

            </td>


            {{-- TOTAL --}}
            <td class="text-right">

                {{ $currency }}.
                {{ number_format(
                    $resumen[
                        'totalVenta'
                    ],
                    2
                ) }}

            </td>


            {{-- CANTIDAD --}}
            <td class="text-center">

                {{ number_format(
                    $resumen[
                        'totalCantidad'
                    ],
                    2
                ) }}

            </td>


            {{-- ESTADO --}}
            <td class="text-center">

                <span
                    class="
                        status
                        {{ $statusClass }}
                    "
                >

                    {{ $statusText }}

                </span>

            </td>


            {{-- CLIENTE / VENDEDOR --}}
            <td class="text-left">

                {{ $item->seller_name
                    ?? 'Consumidor Final'
                }}

            </td>


            {{-- USUARIO --}}
            <td class="text-left">

                {{ $item->user
                    ?? 'N/A'
                }}

            </td>


            {{-- FECHA --}}
            <td class="text-center">

                {{ \Carbon\Carbon::parse(
                    $item->created_at
                )->format(
                    'd-m-Y H:i'
                ) }}

            </td>


        </tr>


    @empty


        <tr>

            <td
                colspan="10"
                class="text-center"
                style="padding: 20px;"
            >

                No existen ventas para
                el período consultado.

            </td>

        </tr>


    @endforelse


    </tbody>

</table>



{{-- ================================================================ --}}
{{-- RESUMEN --}}
{{-- ================================================================ --}}

<table class="summary-wrapper">

    <tr>


        {{-- INFORMACIÓN --}}
        <td class="summary-left">

            <div class="summary-title">

                Información del reporte

            </div>


            <div class="summary-data">

                Reporte generado por:

                <strong>
                    {{ $usuario->name }}
                </strong>

                <br>


                Fecha de consulta:

                <strong>

                    {{ \Carbon\Carbon::now()
                        ->format(
                            'd-m-Y H:i:s'
                        ) }}

                </strong>

                <br>


                Registros encontrados:

                <strong>

                    {{ number_format(
                        $totalVentas
                    ) }}

                </strong>

            </div>

        </td>



        {{-- TOTALES --}}
        <td class="summary-right">

            <table class="totals-table">

                <thead>

                <tr>

                    <th colspan="2">

                        Totales

                    </th>

                </tr>

                </thead>


                <tbody>


                {{-- VENTAS --}}
                <tr>

                    <td>
                        Total de ventas
                    </td>

                    <td class="text-right">

                        {{ number_format(
                            $totalVentas
                        ) }}

                    </td>

                </tr>


                {{-- PRODUCTOS --}}
                <tr>

                    <td>
                        Productos vendidos
                    </td>

                    <td class="text-right">

                        {{ number_format(
                            $totalItems,
                            2
                        ) }}

                    </td>

                </tr>


                {{-- VALOR BRUTO --}}
                <tr>

                    <td>
                        Valor bruto
                    </td>

                    <td class="text-right">

                        {{ $currency }}.
                        {{ number_format(
                            $valorBruto,
                            2
                        ) }}

                    </td>

                </tr>


                {{-- DESCUENTOS --}}
                @if(
                    $totalDescuento > 0
                )

                    <tr>

                        <td>
                            Descuentos (-)
                        </td>

                        <td class="text-right">

                            - {{ $currency }}.
                            {{ number_format(
                                $totalDescuento,
                                2
                            ) }}

                        </td>

                    </tr>

                @endif


                {{-- SUBTOTAL --}}
                <tr>

                    <td>
                        Subtotal sin IVA
                    </td>

                    <td class="text-right">

                        {{ $currency }}.
                        {{ number_format(
                            $subtotal,
                            2
                        ) }}

                    </td>

                </tr>


                {{-- IVA --}}
                <tr>

                    <td>
                        IVA
                    </td>

                    <td class="text-right">

                        {{ $currency }}.
                        {{ number_format(
                            $totalImpuestos,
                            2
                        ) }}

                    </td>

                </tr>


                {{-- TOTAL --}}
                <tr class="grand-total">

                    <td>
                        Importe total
                    </td>

                    <td class="text-right">

                        {{ $currency }}.
                        {{ number_format(
                            $totalImporte,
                            2
                        ) }}

                    </td>

                </tr>


                </tbody>

            </table>

        </td>

    </tr>

</table>



{{-- ================================================================ --}}
{{-- FOOTER --}}
{{-- ================================================================ --}}

<section class="footer">

    <table>

        <tr>


            <td width="33%">

                Sistema
                {{ $empresa->name }}

            </td>


            <td
                width="34%"
                class="text-center"
            >

                Reporte de Ventas

            </td>


            <td
                width="33%"
                class="text-right"
            >

                Página

                <span class="pagenum"></span>

            </td>


        </tr>

    </table>

</section>


</body>

</html>



