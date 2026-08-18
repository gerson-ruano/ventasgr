<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>
        Venta # {{ $getNextSaleNumber }}
    </title>

    <style>
        {!! file_get_contents(public_path('css/pdf_termico.css')) !!}
    </style>
</head>

<body>

@php

    $descuento = (float) ($discount ?? 0);
    $impuestos = (float) ($totalTaxes ?? 0);
    $efectivoRecibido = (float) ($efectivo ?? 0);
    $cambio = (float) ($change ?? 0);

    if (isset($cart) && $cart->isNotEmpty()) {

        $totalProductos = $cart->sum(function ($item) {
            return $item->price * $item->qty;
        });

    } else {

        $totalProductos = $details->sum(function ($item) {
            return $item->price * $item->quantity;
        });

    }

    $totalVenta = $efectivoRecibido - $cambio;

@endphp


{{-- ===================================================== --}}
{{-- EMPRESA --}}
{{-- ===================================================== --}}

<div class="text-center">

    <div class="empresa">
        {{ strtoupper($empresa->name ?? 'EMPRESA') }}
    </div>

    @if(!empty($empresa->nit))
        <div>
            NIT: {{ $empresa->nit }}
        </div>
    @endif

    @if(!empty($empresa->address))
        <div>
            {{ $empresa->address }}
        </div>
    @endif

    @if(!empty($empresa->phone))
        <div>
            Tel: {{ $empresa->phone }}
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
        No. {{ $getNextSaleNumber }}
    </div>

</div>


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- INFORMACIÓN GENERAL --}}
{{-- ===================================================== --}}

<table class="info">

    <tr>
        <td>Fecha:</td>

        <td class="right">
            {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </td>
    </tr>

    <tr>
        <td>Vendedor:</td>

        <td class="right">
            {{ $usuario->name ?? $seller ?? 'N/A' }}
        </td>
    </tr>

</table>


@if(!empty($customer))

    <div class="separator"></div>

    <div>
        <strong>Cliente:</strong>
        {{ $customer['name'] ?? 'Consumidor Final' }}
    </div>

    <div>
        <strong>NIT:</strong>
        {{ $customer['nit'] ?? 'CF' }}
    </div>

    @if(!empty($customer['address']))

        <div>
            <strong>Dirección:</strong>
            {{ $customer['address'] }}
        </div>

    @endif

@endif


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- PRODUCTOS --}}
{{-- ===================================================== --}}

<div class="producto-header">

    <span>PRODUCTOS</span>

</div>


@if(isset($cart) && $cart->isNotEmpty())

    @foreach($cart as $item)

        @php

            $cantidad = (float) $item->qty;
            $precio = (float) $item->price;
            $total = $cantidad * $precio;

        @endphp


        <div class="producto">

            <div class="producto-nombre">

                {{ $item->name }}

            </div>


            <table class="producto-detalle">

                <tr>

                    <td>
                        {{ number_format($cantidad, 2) }}
                        x
                        {{ $currency }}.
                        {{ number_format($precio, 2) }}
                    </td>


                    <td class="right">

                        {{ $currency }}.
                        {{ number_format($total, 2) }}

                    </td>

                </tr>

            </table>

        </div>

    @endforeach


@else


    @foreach($details as $d)

        @php

            $cantidad = (float) $d->quantity;
            $precio = (float) $d->price;
            $total = $cantidad * $precio;

        @endphp


        <div class="producto">

            <div class="producto-nombre">

                {{ $d->product->name }}

            </div>


            <table class="producto-detalle">

                <tr>

                    <td>

                        {{ number_format($cantidad, 2) }}
                        x
                        {{ $currency }}.
                        {{ number_format($precio, 2) }}

                    </td>


                    <td class="right">

                        {{ $currency }}.
                        {{ number_format($total, 2) }}

                    </td>

                </tr>

            </table>

        </div>

    @endforeach

@endif


<div class="separator"></div>


{{-- ===================================================== --}}
{{-- TOTALES --}}
{{-- ===================================================== --}}

<table class="totales">

    <tr>

        <td>
            Subtotal:
        </td>

        <td class="right">

            {{ $currency }}.
            {{ number_format(
                $totalVenta - $impuestos + $descuento,
                2
            ) }}

        </td>

    </tr>


    <tr>

        <td>
            IVA:
        </td>

        <td class="right">

            {{ $currency }}.
            {{ number_format($impuestos, 2) }}

        </td>

    </tr>


    @if($descuento > 0)

        <tr>

            <td>
                Descuento:
            </td>

            <td class="right">

                - {{ $currency }}.
                {{ number_format($descuento, 2) }}

            </td>

        </tr>

    @endif


    <tr class="total">

        <td>
            TOTAL:
        </td>

        <td class="right">

            {{ $currency }}.
            {{ number_format($totalVenta, 2) }}

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

            {{ $metodoPago ?? 'N/A' }}

        </td>

    </tr>


    <tr>

        <td>
            Efectivo:
        </td>

        <td class="right">

            {{ $currency }}.
            {{ number_format($efectivoRecibido, 2) }}

        </td>

    </tr>


    <tr>

        <td>
            Cambio:
        </td>

        <td class="right">

            {{ $currency }}.
            {{ number_format($cambio, 2) }}

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
        {{ strtoupper(
            str_replace(
                '"',
                '',
                $statusMessage ?? ''
            )
        ) }}
    </strong>

</div>


{{-- ===================================================== --}}
{{-- REFERENCIA --}}
{{-- ===================================================== --}}

@if(!empty($customer['ref_page'] ?? null))

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

    Sistema {{ $empresa->name ?? '' }}

</div>


<div class="text-center footer-ticket">

    Venta #{{ $getNextSaleNumber }}

</div>

</body>

</html>
