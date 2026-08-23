<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Cierre de Caja
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
    | Estos valores vienen calculados desde
    | ExportController.
    |
    */

    $totalVentas =
        (int) (
            $resumenGeneral[
                'cantidadVentas'
            ] ?? 0
        );


    $valorBruto =
        (float) (
            $resumenGeneral[
                'valorBruto'
            ] ?? 0
        );


    $totalDescuento =
        (float) (
            $resumenGeneral[
                'descuento'
            ] ?? 0
        );


    $subtotal =
        (float) (
            $resumenGeneral[
                'subtotal'
            ] ?? 0
        );


    $totalImpuestos =
        (float) (
            $resumenGeneral[
                'impuestos'
            ] ?? 0
        );


    $totalImporte =
        (float) (
            $resumenGeneral[
                'totalVenta'
            ] ?? 0
        );


    $totalItems =
        (float) (
            $resumenGeneral[
                'totalCantidad'
            ] ?? 0
        );

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


        {{-- DOCUMENTO --}}
        <td class="header-document">

            <div class="document-title">

                CIERRE DE CAJA

            </div>


            <div class="company-name">

                {{ strtoupper(
                    $empresa->name
                ) }}

            </div>


            <div
                style="
                    font-size: 7.5px;
                    color: #60707e;
                    margin-top: 4px;
                "
            >

                Reporte general de movimientos
                de venta

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
{{-- INFORMACIÓN DEL CIERRE --}}
{{-- ================================================================ --}}

<table class="report-info">

    <tr>


        <td class="label">

            Tipo:

        </td>


        <td class="value">

            <strong>
                Cierre de Caja
            </strong>

        </td>


        <td class="label">

            Usuario consultado:

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

            {{ $fromDate }}

            al

            {{ $toDate }}

        </td>


        <td class="label">

            Fecha consulta:

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
{{-- TABLA PRINCIPAL --}}
{{-- ================================================================ --}}

<table class="table-factus">

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
             * Venta original.
             */
            $item =
                $venta['sale'];


            /*
             * Resumen financiero de
             * esta venta.
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
            <td class="money">

                {{ $currency }}.
                {{ number_format(
                    $resumen[
                        'valorBruto'
                    ],
                    2
                ) }}

            </td>


            {{-- DESCUENTO --}}
            <td class="money">

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
            <td class="money">

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
            <td>

                {{ $item->seller_name
                    ?? 'Consumidor Final'
                }}

            </td>


            {{-- USUARIO --}}
            <td>

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
                style="padding: 25px;"
            >

                No existen ventas registradas
                para el período seleccionado.

            </td>

        </tr>


    @endforelse


    </tbody>

</table>



{{-- ================================================================ --}}
{{-- RESUMEN DEL CIERRE --}}
{{-- ================================================================ --}}

<table class="report-summary">

    <tr>


        {{-- INFORMACIÓN --}}
        <td class="report-summary-left">


            <div class="report-summary-title">

                Información del cierre

            </div>


            <div class="report-summary-data">


                Período consultado:

                <strong>

                    {{ $fromDate }}

                    al

                    {{ $toDate }}

                </strong>


                <br>


                Usuario consultado:

                <strong>
                    {{ $user }}
                </strong>


                <br>


                Cierre generado por:

                <strong>
                    {{ $usuario->name }}
                </strong>


                <br>


                Fecha de generación:

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
        <td class="report-summary-right">


            <table class="report-totals">


                <thead>

                <tr>

                    <th colspan="2">

                        Totales del cierre

                    </th>

                </tr>

                </thead>


                <tbody>


                {{-- TOTAL DE VENTAS --}}
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


                    <td class="money">

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


                        <td class="money">

                            - {{ $currency }}.
                            {{ number_format(
                                $totalDescuento,
                                2
                            ) }}

                        </td>

                    </tr>

                @endif


                {{-- SUBTOTAL SIN IVA --}}
                <tr>

                    <td>

                        Subtotal sin IVA

                    </td>


                    <td class="money">

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


                    <td class="money">

                        {{ $currency }}.
                        {{ number_format(
                            $totalImpuestos,
                            2
                        ) }}

                    </td>

                </tr>


                {{-- TOTAL FINAL --}}
                <tr class="report-total-final">


                    <td>

                        Importe total

                    </td>


                    <td class="money">

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
{{-- CONTROL DE CUADRE --}}
{{-- ================================================================ --}}

<table
    class="report-info"
    style="margin-top: 10px;"
>

    <tr>

        <td class="label">

            Bruto:

        </td>


        <td class="value">

            {{ $currency }}.
            {{ number_format(
                $valorBruto,
                2
            ) }}

        </td>


        <td class="label">

            Descuentos:

        </td>


        <td class="value">

            - {{ $currency }}.
            {{ number_format(
                $totalDescuento,
                2
            ) }}

        </td>

    </tr>


    <tr>

        <td class="label">

            Neto:

        </td>


        <td class="value">

            {{ $currency }}.
            {{ number_format(
                $totalImporte,
                2
            ) }}

        </td>


        <td class="label">

            IVA incluido:

        </td>


        <td class="value">

            {{ $currency }}.
            {{ number_format(
                $totalImpuestos,
                2
            ) }}

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

                Cierre de Caja

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


<script type="text/php">

    @include(
        'pdf.partials.pdf_script'
    )

</script>


</body>

</html>
