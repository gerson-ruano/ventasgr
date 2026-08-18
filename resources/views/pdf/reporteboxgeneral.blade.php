<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Cierre de Caja</title>

    {{-- CSS general estilo Factus --}}
    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
        {!! file_get_contents(public_path('css/pdf_horizontal.css')) !!}
    </style>

    {{-- Este reporte necesita formato horizontal --}}

</head>


@php

    /*
    |--------------------------------------------------------------------------
    | TRADUCCIONES
    |--------------------------------------------------------------------------
    */

    $statusTranslations = [

        'PAID' => 'Pagado',

        'CANCELLED' => 'Anulado',

        'PENDING' => 'Pendiente'

    ];


    /*
    |--------------------------------------------------------------------------
    | TOTALES GENERALES
    |--------------------------------------------------------------------------
    */

    $totalVentas = $data->count();

    $totalImporte = $data->sum('total');

    $totalItems = $data->sum('items');

@endphp


<body>


{{-- ================================================================ --}}
{{-- ENCABEZADO --}}
{{-- ================================================================ --}}

<table class="header-table">

    <tr>


        {{-- LOGO --}}
        <td class="header-logo">

            @if(!empty($empresa->imagen_pdf))

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

                {{ strtoupper($empresa->name) }}

            </div>


            <div
                style="
                    font-size: 7.5px;
                    color: #60707e;
                    margin-top: 4px;
                "
            >

                Reporte general de movimientos de venta

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

            {{ $fromDate }}

            al

            {{ $toDate }}

        </td>


        <td class="label">

            Fecha consulta:

        </td>


        <td class="value">

            {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}

        </td>


    </tr>

</table>



{{-- ================================================================ --}}
{{-- TABLA PRINCIPAL --}}
{{-- ================================================================ --}}

<table class="table-factus">

    <thead>

    <tr>


        <th width="5%">

            #

        </th>


        <th width="8%">

            Venta

        </th>


        <th width="13%">

            Importe

        </th>


        <th width="9%">

            Cantidad

        </th>


        <th width="11%">

            Estado

        </th>


        <th width="16%">

            Cliente / Vendedor

        </th>


        <th width="17%">

            Usuario

        </th>


        <th width="21%">

            Fecha / Hora

        </th>


    </tr>

    </thead>


    <tbody>


    @forelse($data as $index => $item)


        @php

            $statusText =
                $statusTranslations[$item->status]
                ?? $item->status;


            $statusClass = match($item->status) {

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


            <td class="text-center">

                {{ $index + 1 }}

            </td>


            <td class="text-center">

                {{ $item->id }}

            </td>


            <td class="money">

                {{ $currency }}.
                {{ number_format(
                    $item->total,
                    2
                ) }}

            </td>


            <td class="text-center">

                {{ $item->items }}

            </td>


            <td class="text-center">

                <span class="status {{ $statusClass }}">

                    {{ $statusText }}

                </span>

            </td>


            <td>

                {{ $item->seller_name ?? 'Consumidor Final' }}

            </td>


            <td>

                {{ $user }}

            </td>


            <td class="text-center">

                {{ $item->created_at
                    ->format('d-m-Y H:i') }}

            </td>


        </tr>


    @empty


        <tr>

            <td
                colspan="8"
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


                Cierre generado por:

                <strong>

                    {{ $usuario->name }}

                </strong>


                <br>


                Fecha de generación:

                <strong>

                    {{ \Carbon\Carbon::now()
                        ->format('d-m-Y H:i:s') }}

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


                <tr>

                    <td>

                        Productos vendidos

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $totalItems
                        ) }}

                    </td>

                </tr>


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

    @include('pdf.partials.pdf_script')

</script>


</body>

</html>


