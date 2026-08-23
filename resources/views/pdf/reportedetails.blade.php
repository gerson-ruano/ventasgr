<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Detalle Venta #{{ $getNextSaleNumber }}
    </title>


    <style>
        {!! file_get_contents(
            public_path('css/pdf.css')
        ) !!}
    </style>

</head>


@php

    /*
    |--------------------------------------------------------------------------
    | ESTADO
    |--------------------------------------------------------------------------
    */

    $statusTranslations = [

        'PAID' =>
            'PAGADO',

        'PENDING' =>
            'PENDIENTE',

        'CANCELLED' =>
            'ANULADO',
    ];


    $statusText =
        $statusTranslations[
            $sale->status
        ]
        ?? $sale->status;


    $statusClass =
        match($sale->status) {

            'PAID' =>
                'status-paid',

            'PENDING' =>
                'status-pending',

            'CANCELLED' =>
                'status-cancelled',

            default =>
                ''
        };


    /*
    |--------------------------------------------------------------------------
    | RESUMEN
    |--------------------------------------------------------------------------
    |
    | Ningún cálculo financiero se realiza
    | en esta vista.
    |
    */

    $valorBruto = $resumen['valorBruto'];

    $descuento =
        $resumen['descuento'];

    $subtotal =
        $resumen['subtotal'];

    $impuestos =
        $resumen['impuestos'];

    $totalVenta =
        $resumen['totalVenta'];

    $totalCantidad =
        $resumen['totalCantidad'];


    /*
    |--------------------------------------------------------------------------
    | PAGO
    |--------------------------------------------------------------------------
    */

    $efectivo =
        (float) (
            $sale->cash
            ?? 0
        );


    $cambio =
        (float) (
            $sale->change
            ?? 0
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

            @if(!empty($empresa->imagen))

                <img
                    src="{{ $empresa->imagen }}"
                    alt="{{ $empresa->name ?? 'Empresa' }}"
                    class="invoice-logo"
                >

            @endif

        </td>



        {{-- DOCUMENTO --}}
        <td class="header-document">

            <div class="document-title">

                DETALLE DE VENTA

            </div>


            <div class="document-number">

                VENTA #
                {{ $getNextSaleNumber }}

            </div>


            <div class="company-name">

                {{ strtoupper(
                    $empresa->name
                    ?? 'EMPRESA'
                ) }}

            </div>


            <span class="status {{ $statusClass }}">

                {{ $statusText }}

            </span>

        </td>



        {{-- EMPRESA --}}
        <td class="header-company">

            <div class="company-data">

                <strong>
                    {{ $empresa->name ?? '' }}
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
{{-- CLIENTE / DATOS DE LA VENTA --}}
{{-- ================================================================ --}}

<table class="general-info">

    <tr>


        {{-- CLIENTE --}}
        <td class="customer-column">

            <table class="info-table">


                @if($seller_name == 'Cliente')

                    <tr>

                        <td class="label">
                            Cliente:
                        </td>

                        <td>

                            <strong>

                                {{ $customer['name']
                                    ?? 'Consumidor Final'
                                }}

                            </strong>

                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            NIT:
                        </td>

                        <td>

                            {{ $customer['nit']
                                ?? 'CF'
                            }}

                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Dirección:
                        </td>

                        <td>

                            {{ $customer['address']
                                ?? 'N/A'
                            }}

                        </td>

                    </tr>


                    @if(!empty(
                        $customer[
                            'departamento_nombre'
                        ]
                        ?? null
                    ))

                        <tr>

                            <td class="label">
                                Departamento:
                            </td>

                            <td>

                                {{ $customer[
                                    'departamento_nombre'
                                ] }}

                            </td>

                        </tr>

                    @endif


                    @if(!empty(
                        $customer[
                            'municipio_nombre'
                        ]
                        ?? null
                    ))

                        <tr>

                            <td class="label">
                                Municipio:
                            </td>

                            <td>

                                {{ $customer[
                                    'municipio_nombre'
                                ] }}

                            </td>

                        </tr>

                    @endif


                @else

                    <tr>

                        <td class="label">
                            Vendedor:
                        </td>

                        <td>

                            <strong>
                                {{ $seller_name }}
                            </strong>

                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Cliente:
                        </td>

                        <td>
                            Consumidor Final
                        </td>

                    </tr>

                @endif


            </table>

        </td>



        {{-- DOCUMENTO --}}
        <td class="document-column">

            <table class="info-table">


                <tr>

                    <td class="label">
                        Fecha:
                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse(
                            $sale->created_at
                        )->format('d-m-Y') }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Hora:
                    </td>

                    <td>

                        {{ \Carbon\Carbon::parse(
                            $sale->created_at
                        )->format('H:i:s') }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Estado:
                    </td>

                    <td>
                        {{ $statusText }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Método:
                    </td>

                    <td>
                        {{ $metodoPago ?? 'N/A' }}
                    </td>

                </tr>


                @if(!empty(
                    $customer['ref_page']
                    ?? null
                ))

                    <tr>

                        <td class="label">
                            Referencia:
                        </td>

                        <td>
                            {{ $customer['ref_page'] }}
                        </td>

                    </tr>

                @endif


            </table>

        </td>


    </tr>

</table>



{{-- ================================================================ --}}
{{-- PRODUCTOS --}}
{{-- ================================================================ --}}

<table class="table-factus">

    <thead>

    <tr>

        <th width="5%">
            #
        </th>


        <th width="11%">
            Código
        </th>


        <th width="28%">
            Descripción
        </th>


        <th width="10%">
            Cant.
        </th>


        <th width="14%">
            Val. Unit.
        </th>


        <th width="16%">
            Descuento
        </th>


        <th width="16%">
            Total
        </th>

    </tr>

    </thead>


    <tbody>


    @forelse($lineas as $linea)

        <tr>


            {{-- # --}}
            <td class="text-center">

                {{ $loop->iteration }}

            </td>



            {{-- CÓDIGO --}}
            <td class="text-center">

                {{ $linea['codigo'] }}

            </td>



            {{-- PRODUCTO --}}
            <td>

                {{ $linea['nombre'] }}

            </td>



            {{-- CANTIDAD --}}
            <td class="text-center">

                {{ number_format(
                    $linea['cantidad'],
                    2
                ) }}

            </td>



            {{-- PRECIO UNITARIO --}}
            <td class="money">

                {{ $currency }}.
                {{ number_format(
                    $linea['precio'],
                    2
                ) }}

            </td>



            {{-- DESCUENTO --}}
            <td class="money">

                @if(
                    $linea['descuento'] > 0
                )

                    - {{ $currency }}.
                    {{ number_format(
                        $linea['descuento'],
                        2
                    ) }}

                    <br>

                    <small>

                        {{ number_format(
                            $linea[
                                'porcentaje_descuento'
                            ],
                            2
                        ) }}%

                    </small>

                @else

                    {{ $currency }}. 0.00

                @endif

            </td>



            {{-- TOTAL --}}
            <td class="money">

                {{ $currency }}.
                {{ number_format(
                    $linea['total'],
                    2
                ) }}

            </td>


        </tr>


    @empty

        <tr>

            <td
                colspan="7"
                class="text-center"
                style="padding: 20px;"
            >

                No existen productos registrados
                para esta venta.

            </td>

        </tr>

    @endforelse


    </tbody>

</table>



{{-- ================================================================ --}}
{{-- OBSERVACIONES + RESUMEN --}}
{{-- ================================================================ --}}

<table class="summary-table">

    <tr>


        <th class="observations-title">

            Información de la venta

        </th>


        <th class="totals-title">

            Totales

        </th>


    </tr>


    <tr>


        {{-- INFORMACIÓN --}}
        <td class="observations-cell">


            Estado:

            <strong>
                {{ $statusText }}
            </strong>


            <br><br>


            Registrada por:

            <strong>
                {{ $usuario->name ?? 'N/A' }}
            </strong>


            @if(
                $seller_name != 'Cliente'
            )

                <br><br>


                Vendedor:

                <strong>
                    {{ $seller_name }}
                </strong>

            @endif


            @if(!empty(
                $customer['ref_page']
                ?? null
            ))

                <br><br>


                Referencia de pago:

                <strong>
                    {{ $customer['ref_page'] }}
                </strong>

            @endif


            @if(
                $sale->created_at !=
                $sale->updated_at
            )

                <br><br>


                Última modificación:

                <strong>

                    {{ \Carbon\Carbon::parse(
                        $sale->updated_at
                    )->format(
                        'd-m-Y H:i'
                    ) }}

                </strong>

            @endif


        </td>



        {{-- TOTALES --}}
        <td class="totals-cell">

            <table class="totals-inner">


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



                {{-- DESCUENTO --}}
                @if($descuento > 0)

                    <tr>

                        <td>
                            Descuento (-)
                        </td>

                        <td class="money">

                            - {{ $currency }}.
                            {{ number_format(
                                $descuento,
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
                            $impuestos,
                            2
                        ) }}

                    </td>

                </tr>



                {{-- CANTIDAD --}}
                <tr>

                    <td>
                        Cantidad
                    </td>

                    <td class="money">

                        {{ number_format(
                            $totalCantidad,
                            2
                        ) }}

                    </td>

                </tr>



                {{-- TOTAL --}}
                <tr class="grand-total">

                    <td>
                        Total venta
                    </td>

                    <td class="money">

                        {{ $currency }}.
                        {{ number_format(
                            $totalVenta,
                            2
                        ) }}

                    </td>

                </tr>


            </table>

        </td>


    </tr>

</table>



{{-- ================================================================ --}}
{{-- PAGO --}}
{{-- ================================================================ --}}

<table class="payment-section">

    <tr>


        {{-- OPERACIÓN --}}
        <td class="operation-column">

            <div class="section-title">

                Información de Operación

            </div>


            <div class="section-label">
                Operación:
            </div>

            <div class="section-value">
                Venta
            </div>


            <div class="section-label">
                Estado:
            </div>

            <div class="section-value">
                {{ $statusText }}
            </div>


            <div class="section-label">
                Productos vendidos:
            </div>

            <div class="section-value">

                {{ number_format(
                    $totalCantidad,
                    2
                ) }}

            </div>

        </td>



        {{-- PAGO --}}
        <td class="payment-column">

            <div class="section-title">

                Detalles de Pago

            </div>


            <div class="section-label">
                Forma de pago:
            </div>

            <div class="section-value">

                {{ $metodoPago
                    ?? 'N/A'
                }}

            </div>


            <div class="section-label">
                Efectivo recibido:
            </div>

            <div class="section-value">

                {{ $currency }}.
                {{ number_format(
                    $efectivo,
                    2
                ) }}

            </div>


            <div class="section-label">
                Cambio:
            </div>

            <div class="section-value">

                {{ $currency }}.
                {{ number_format(
                    $cambio,
                    2
                ) }}

            </div>


            <div class="section-label">
                Monto:
            </div>

            <div class="payment-total">

                {{ $currency }}.
                {{ number_format(
                    $totalVenta,
                    2
                ) }}

            </div>

        </td>


    </tr>

</table>



{{-- ================================================================ --}}
{{-- AUDITORÍA --}}
{{-- ================================================================ --}}

<table class="audit-table">

    <tr>


        <td class="label">
            Usuario:
        </td>

        <td>
            {{ $usuario->name ?? 'N/A' }}
        </td>


        <td class="label">
            Fecha de venta:
        </td>

        <td>

            {{ \Carbon\Carbon::parse(
                $sale->created_at
            )->format(
                'd-m-Y H:i'
            ) }}

        </td>


    </tr>


    <tr>


        <td class="label">
            Fecha de consulta:
        </td>

        <td>

            {{ \Carbon\Carbon::now()
                ->format(
                    'd-m-Y H:i:s'
                ) }}

        </td>


        <td class="label">
            Modificado:
        </td>

        <td>

            @if(
                $sale->created_at !=
                $sale->updated_at
            )

                {{ \Carbon\Carbon::parse(
                    $sale->updated_at
                )->format(
                    'd-m-Y H:i'
                ) }}

            @else

                Sin cambios

            @endif

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
                {{ $empresa->name ?? '' }}

            </td>


            <td
                width="34%"
                class="text-center"
            >

                Detalle de Venta

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

