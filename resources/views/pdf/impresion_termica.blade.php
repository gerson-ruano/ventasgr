<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Venta #{{ $getNextSaleNumber }}
    </title>

    <style>
        {!! file_get_contents(
            public_path('css/pdf_termico.css')
        ) !!}
    </style>

</head>


@php

    /*
    |--------------------------------------------------------------------------
    | RESUMEN DE VENTA
    |--------------------------------------------------------------------------
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
    | FECHA
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


{{-- ===================================================== --}}
{{-- EMPRESA --}}
{{-- ===================================================== --}}

<div class="text-center">

    <div class="empresa">

        {{ strtoupper(
            $empresa->name
            ?? 'EMPRESA'
        ) }}

    </div>


    @if(!empty($empresa->nit))

        <div>

            NIT:
            {{ $empresa->nit }}

        </div>

    @endif


    @if(!empty($empresa->address))

        <div>
            {{ $empresa->address }}
        </div>

    @endif


    @if(!empty($empresa->phone))

        <div>

            Tel:
            {{ $empresa->phone }}

        </div>

    @endif

</div>


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- DOCUMENTO --}}
{{-- ===================================================== --}}

<div class="text-center">

    <div class="titulo">

        COMPROBANTE DE VENTA

    </div>


    <div class="numero">

        No.
        {{ $getNextSaleNumber }}

    </div>

</div>


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- INFORMACIÓN GENERAL --}}
{{-- ===================================================== --}}

<table class="info">

    <tr>

        <td>
            Fecha:
        </td>

        <td class="right">

            {{ $fechaVenta->format(
                'd/m/Y H:i'
            ) }}

        </td>

    </tr>


    <tr>

        <td>
            Vendedor:
        </td>

        <td class="right">

            {{ $seller
                ?? $usuario->name
                ?? 'N/A'
            }}

        </td>

    </tr>

</table>


{{-- ===================================================== --}}
{{-- CLIENTE --}}
{{-- ===================================================== --}}

<div class="separator"></div>


<div>

    <strong>
        Cliente:
    </strong>

    {{ $customer['name']
        ?? 'Consumidor Final'
    }}

</div>


<div>

    <strong>
        NIT:
    </strong>

    {{ $customer['nit']
        ?? 'CF'
    }}

</div>


@if(!empty(
    $customer['address']
    ?? null
))

    <div>

        <strong>
            Dirección:
        </strong>

        {{ $customer['address'] }}

    </div>

@endif


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- PRODUCTOS --}}
{{-- ===================================================== --}}

<div class="producto-header">

    <span>
        PRODUCTOS
    </span>

</div>


@forelse($lineas as $linea)

    <div class="producto">


        {{-- NOMBRE --}}
        <div class="producto-nombre">

            {{ $linea['nombre'] }}

        </div>


        {{-- CÓDIGO --}}
        @if(
            !empty($linea['codigo']) &&
            $linea['codigo'] != '-'
        )

            <div style="font-size: 8px;">

                Cod:
                {{ $linea['codigo'] }}

            </div>

        @endif


        <table class="producto-detalle">


            {{-- PRECIO ORIGINAL --}}
            <tr>

                <td>

                    {{ number_format(
                        $linea['cantidad'],
                        2
                    ) }}

                    x

                    {{ $currency }}.

                    {{ number_format(
                        $linea['precio'],
                        2
                    ) }}

                </td>


                <td class="right">

                    {{ $currency }}.

                    {{ number_format(
                        $linea['subtotal'],
                        2
                    ) }}

                </td>

            </tr>


            {{-- DESCUENTO --}}
            @if($linea['descuento'] > 0)

                <tr>

                    <td>

                        Desc.

                        {{ number_format(
                            $linea['porcentaje_descuento'],
                            2
                        ) }}%

                    </td>


                    <td class="right">

                        - {{ $currency }}.

                        {{ number_format(
                            $linea['descuento'],
                            2
                        ) }}

                    </td>

                </tr>


                {{-- TOTAL DESPUÉS DE DESCUENTO --}}
                <tr>

                    <td>

                        <strong>
                            Total producto
                        </strong>

                    </td>


                    <td class="right">

                        <strong>

                            {{ $currency }}.

                            {{ number_format(
                                $linea['total'],
                                2
                            ) }}

                        </strong>

                    </td>

                </tr>

            @endif


        </table>

    </div>


@empty

    <div class="text-center">

        No existen productos
        registrados.

    </div>

@endforelse


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- TOTALES --}}
{{-- ===================================================== --}}

<table class="totales">


    {{-- VALOR BRUTO --}}
    <tr>

        <td>
            Valor bruto:
        </td>


        <td class="right">

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
                Descuento:
            </td>


            <td class="right">

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
            Subtotal sin IVA:
        </td>


        <td class="right">

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
            IVA:
        </td>


        <td class="right">

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
            Cantidad:
        </td>


        <td class="right">

            {{ number_format(
                $totalCantidad,
                2
            ) }}

        </td>

    </tr>


    {{-- TOTAL FINAL --}}
    <tr class="total">

        <td>

            TOTAL:

        </td>


        <td class="right">

            {{ $currency }}.

            {{ number_format(
                $totalVenta,
                2
            ) }}

        </td>

    </tr>

</table>


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- PAGO --}}
{{-- ===================================================== --}}

<table class="info">

    <tr>

        <td>
            Método:
        </td>


        <td class="right">

            {{ $metodoPago
                ?? 'N/A'
            }}

        </td>

    </tr>


    <tr>

        <td>
            Efectivo:
        </td>


        <td class="right">

            {{ $currency }}.

            {{ number_format(
                $efectivoRecibido,
                2
            ) }}

        </td>

    </tr>


    <tr>

        <td>
            Cambio:
        </td>


        <td class="right">

            {{ $currency }}.

            {{ number_format(
                $cambio,
                2
            ) }}

        </td>

    </tr>

</table>


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- ESTADO --}}
{{-- ===================================================== --}}

<div class="text-center">

    <strong>

        ESTADO:

        {{ $estado ?: 'N/A' }}

    </strong>

</div>


{{-- ===================================================== --}}
{{-- REFERENCIA --}}
{{-- ===================================================== --}}

@if(!empty(
    $customer['ref_page']
    ?? null
))

    <div class="text-center referencia">

        Ref:
        {{ $customer['ref_page'] }}

    </div>

@endif


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- MENSAJE FINAL --}}
{{-- ===================================================== --}}

<div class="text-center gracias">

    ¡GRACIAS POR SU COMPRA!

</div>


<div class="text-center">

    Sistema
    {{ $empresa->name ?? '' }}

</div>


<div class="text-center footer-ticket">

    Venta #
    {{ $getNextSaleNumber }}

</div>


</body>

</html>
