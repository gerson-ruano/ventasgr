<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Venta #{{ $getNextSaleNumber }}
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
    | RESUMEN DE LA VENTA
    |--------------------------------------------------------------------------
    |
    | Todos estos valores ya vienen calculados
    | desde ExportController.
    |
    */

    $valorBruto =
        (float) ($resumen['valorBruto'] ?? 0);

    $descuento =
        (float) ($resumen['descuento'] ?? 0);

    $subtotal =
        (float) ($resumen['subtotal'] ?? 0);

    $impuestos =
        (float) ($resumen['impuestos'] ?? 0);

    $totalVenta =
        (float) ($resumen['totalVenta'] ?? 0);

    $totalCantidad =
        (float) ($resumen['totalCantidad'] ?? 0);


    /*
    |--------------------------------------------------------------------------
    | DATOS DE PAGO
    |--------------------------------------------------------------------------
    */

    $efectivoRecibido =
        (float) ($efectivo ?? 0);

    $cambio =
        (float) ($change ?? 0);


    /*
    |--------------------------------------------------------------------------
    | ESTADO
    |--------------------------------------------------------------------------
    */

    $statusTranslations = [
        'PAID' => 'PAGADO',
        'PENDING' => 'PENDIENTE',
        'CANCELLED' => 'ANULADO',
    ];


    $estadoOriginal =
        strtoupper(
            str_replace(
                '"',
                '',
                $statusMessage ?? ''
            )
        );


    $estado =
        $statusTranslations[$estadoOriginal]
        ?? $estadoOriginal;


    /*
    |--------------------------------------------------------------------------
    | FECHA DE LA VENTA
    |--------------------------------------------------------------------------
    */

    $fechaVenta =
        isset($sale) &&
        $sale &&
        !empty($sale->created_at)
            ? \Carbon\Carbon::parse(
                $sale->created_at
            )
            : \Carbon\Carbon::now();

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
                    class="invoice-logo">

            @endif

        </td>


        {{-- DOCUMENTO --}}
        <td class="header-document">

            <div class="document-title">
                COMPROBANTE DE VENTA
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


            @if(!empty($estado))

                <div>
                    <strong>
                        {{ $estado }}
                    </strong>
                </div>

            @endif

        </td>


        {{-- DATOS EMPRESA --}}
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


                @if(!empty($empresa->email))

                    <br>

                    Email:

                    <strong>
                        {{ $empresa->email }}
                    </strong>

                @endif


                @if(!empty($empresa->phone))

                    <br>

                    Teléfono:

                    <strong>
                        {{ $empresa->phone }}
                    </strong>

                @endif

            </div>

        </td>

    </tr>

</table>


{{-- ================================================================ --}}
{{-- CLIENTE / INFORMACIÓN DE VENTA --}}
{{-- ================================================================ --}}

<table class="general-info">

    <tr>

        {{-- CLIENTE --}}
        <td class="customer-column">

            <table class="info-table">

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


                @if(!empty(
                    $customer['address']
                    ?? null
                ))

                    <tr>

                        <td class="label">
                            Dirección:
                        </td>

                        <td>
                            {{ $customer['address'] }}
                        </td>

                    </tr>

                @endif


                @if(!empty(
                    $customer['departamento_nombre']
                    ?? null
                ))

                    <tr>

                        <td class="label">
                            Departamento:
                        </td>

                        <td>
                            {{ $customer['departamento_nombre'] }}
                        </td>

                    </tr>

                @endif


                @if(!empty(
                    $customer['municipio_nombre']
                    ?? null
                ))

                    <tr>

                        <td class="label">
                            Municipio:
                        </td>

                        <td>
                            {{ $customer['municipio_nombre'] }}
                        </td>

                    </tr>

                @endif

            </table>

        </td>


        {{-- DATOS DE VENTA --}}
        <td class="document-column">

            <table class="info-table">

                <tr>

                    <td class="label">
                        Fecha:
                    </td>

                    <td>

                        {{ $fechaVenta->format(
                            'd-m-Y'
                        ) }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Hora:
                    </td>

                    <td>

                        {{ $fechaVenta->format(
                            'H:i:s'
                        ) }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Vendedor:
                    </td>

                    <td>

                        {{ $seller
                            ?? $usuario->name
                            ?? 'N/A'
                        }}

                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Método:
                    </td>

                    <td>

                        {{ $metodoPago
                            ?? 'N/A'
                        }}

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

            {{-- NÚMERO --}}
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

                @if($linea['descuento'] > 0)

                    - {{ $currency }}.
                    {{ number_format(
                        $linea['descuento'],
                        2
                    ) }}

                    <br>

                    <small>

                        {{ number_format(
                            $linea['porcentaje_descuento'],
                            2
                        ) }}%

                    </small>

                @else

                    {{ $currency }}. 0.00

                @endif

            </td>


            {{-- TOTAL DESPUÉS DE DESCUENTO --}}
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
{{-- OBSERVACIONES + TOTALES --}}
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

            Venta:

            <strong>
                #{{ $getNextSaleNumber }}
            </strong>


            <br><br>


            Estado:

            <strong>
                {{ $estado ?: 'N/A' }}
            </strong>


            <br><br>


            Registrada por:

            <strong>
                {{ $usuario->name ?? 'N/A' }}
            </strong>


            @if(!empty($seller))

                <br><br>

                Vendedor:

                <strong>
                    {{ $seller }}
                </strong>

            @endif


            @if(!empty(
                $customer['ref_page']
                ?? null
            ))

                <br><br>

                Referencia:

                <strong>
                    {{ $customer['ref_page'] }}
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


                {{-- TOTAL FINAL --}}
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
                Tipo de operación
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
                {{ $estado ?: 'N/A' }}
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
                    $efectivoRecibido,
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

                Comprobante de Venta

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
