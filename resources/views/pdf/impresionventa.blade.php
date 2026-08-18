<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>
        Venta # {{ $getNextSaleNumber }}
    </title>

    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
    </style>
</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | DATOS GENERALES
    |--------------------------------------------------------------------------
    */

    $descuento = (float) ($discount ?? 0);
    $impuestos = (float) ($totalTaxes ?? 0);
    $efectivoRecibido = (float) ($efectivo ?? 0);
    $cambio = (float) ($change ?? 0);

    /*
    |--------------------------------------------------------------------------
    | VALOR BRUTO
    |--------------------------------------------------------------------------
    */

    if (isset($cart) && $cart->isNotEmpty()) {

        $valorBruto = $cart->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $totalCantidad = $cart->sum('qty');

    } else {

        $valorBruto = $details->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalCantidad = $details->sum('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | VALORES FINALES
    |--------------------------------------------------------------------------
    |
    | Mantengo la lógica que ya utilizabas.
    |
    */

    $totalFactura = $efectivoRecibido - $cambio;

    $subtotal = $totalFactura
        - $descuento
        - $impuestos;

@endphp


{{-- ============================================================= --}}
{{-- ENCABEZADO --}}
{{-- ============================================================= --}}

<table class="header-table">

    <tr>

        {{-- LOGO / EMPRESA --}}
        <td class="header-logo">

            @include(
                'livewire.components.empresa_header',
                ['empresa' => $empresa]
            )

        </td>


        {{-- INFORMACIÓN DOCUMENTO --}}
        <td class="header-document">

            <div class="document-title">
                COMPROBANTE DE VENTA
            </div>

            <div class="document-number">
                VENTA # {{ $getNextSaleNumber }}
            </div>

            <div class="company-name">
                {{ strtoupper($empresa->name ?? '') }}
            </div>

            <div class="status-text">

                Estado:

                <strong>
                    {{ str_replace('"', '', $statusMessage ?? 'N/A') }}
                </strong>

            </div>

        </td>


        {{-- QR OPCIONAL --}}
        <td class="header-qr">

            @if(isset($qrCode) && !empty($qrCode))

                <img
                    src="{{ $qrCode }}"
                    class="qr-image"
                    alt="Código QR"
                >

            @else

                <div class="qr-placeholder">
                    DOCUMENTO<br>
                    DE VENTA
                </div>

            @endif

        </td>

    </tr>

</table>



{{-- ============================================================= --}}
{{-- DATOS CLIENTE + FECHAS --}}
{{-- ============================================================= --}}

<table class="general-info-table">

    <tr>

        {{-- CLIENTE --}}
        <td class="customer-information">

            <table class="info-table">

                <tr>
                    <td class="label">
                        Cliente:
                    </td>

                    <td>
                        <strong>
                            {{ $customer['name'] ?? $seller ?? 'Consumidor Final' }}
                        </strong>
                    </td>
                </tr>


                <tr>
                    <td class="label">
                        NIT:
                    </td>

                    <td>
                        {{ $customer['nit'] ?? 'CF' }}
                    </td>
                </tr>


                <tr>
                    <td class="label">
                        Dirección:
                    </td>

                    <td>
                        {{ $customer['address'] ?? 'N/A' }}
                    </td>
                </tr>


                <tr>
                    <td class="label">
                        Departamento:
                    </td>

                    <td>
                        {{ $customer['departamento_nombre'] ?? 'N/A' }}
                    </td>
                </tr>


                <tr>
                    <td class="label">
                        Municipio:
                    </td>

                    <td>
                        {{ $customer['municipio_nombre'] ?? 'N/A' }}
                    </td>
                </tr>


                @if(!empty($customer['email'] ?? null))

                    <tr>

                        <td class="label">
                            Correo:
                        </td>

                        <td>
                            {{ $customer['email'] }}
                        </td>

                    </tr>

                @endif

            </table>

        </td>


        {{-- FECHAS --}}
        <td class="date-information">

            <table class="info-table">

                <tr>

                    <td class="label">
                        Fecha:
                    </td>

                    <td>
                        {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Hora:
                    </td>

                    <td>
                        {{ \Carbon\Carbon::now()->format('H:i:s') }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Vendedor:
                    </td>

                    <td>
                        {{ $usuario->name ?? $seller ?? 'N/A' }}
                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>



{{-- ============================================================= --}}
{{-- PRODUCTOS --}}
{{-- ============================================================= --}}

<table class="products-table">

    <thead>

    <tr>

        <th width="5%">
            #
        </th>

        <th width="31%">
            Descripción
        </th>

        <th width="15%">
            Val. Unit.
        </th>

        <th width="10%">
            Cantidad
        </th>

        <th width="13%">
            Descuento
        </th>

        <th width="14%">
            Total
        </th>

    </tr>

    </thead>


    <tbody>

    @if(isset($cart) && $cart->isNotEmpty())


        @foreach($cart as $item)

            @php

                $cantidad = (float) $item->qty;
                $precio = (float) $item->price;

                $totalProducto =
                    $precio * $cantidad;

            @endphp

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>


                <td>
                    {{ $item->name }}
                </td>


                <td class="money">
                    {{ $currency }}.
                    {{ number_format($precio, 2) }}
                </td>


                <td class="center">
                    {{ number_format($cantidad, 2) }}
                </td>


                <td class="money">
                    {{ $currency }}.
                    0.00
                </td>


                <td class="money">
                    {{ $currency }}.
                    {{ number_format($totalProducto, 2) }}
                </td>

            </tr>

        @endforeach


    @else


        @foreach($details as $d)

            @php

                $cantidad = (float) $d->quantity;
                $precio = (float) $d->price;

                $totalProducto =
                    $precio * $cantidad;

            @endphp


            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>


                <td class="center">
                    {{ $d->product->id ?? '-' }}
                </td>


                <td>
                    {{ $d->product->name }}
                </td>


                <td class="money">
                    {{ $currency }}.
                    {{ number_format($precio, 2) }}
                </td>


                <td class="center">
                    {{ number_format($cantidad, 2) }}
                </td>


                <td class="money">
                    {{ $currency }}.
                    0.00
                </td>


                <td class="money">
                    {{ $currency }}.
                    {{ number_format($totalProducto, 2) }}
                </td>

            </tr>

        @endforeach

    @endif

    </tbody>

</table>



{{-- ============================================================= --}}
{{-- OBSERVACIONES + TOTALES --}}
{{-- ============================================================= --}}

<table class="summary-table">

    <tr>

        <th class="observations-title">
            Observaciones
        </th>

        <th class="totals-title">
            Totales
        </th>

    </tr>


    <tr>

        {{-- OBSERVACIONES --}}
        <td class="observations-cell">

            @if(!empty($customer['ref_page'] ?? null))

                <strong>
                    Referencia de pago:
                </strong>

                {{ $customer['ref_page'] }}

                <br><br>

            @endif


            Venta realizada por:

            <strong>
                {{ $usuario->name ?? $seller ?? 'N/A' }}
            </strong>

            <br><br>

            Estado:

            <strong>
                {{ str_replace('"', '', $statusMessage ?? 'N/A') }}
            </strong>

        </td>


        {{-- TOTALES --}}
        <td class="totals-cell">

            <table class="totals-inner">

                <tr>
                    <td>
                        Valor bruto
                    </td>

                    <td class="money">
                        {{ $currency }}.
                        {{ number_format($valorBruto, 2) }}
                    </td>
                </tr>


                <tr>
                    <td>
                        Base imponible
                    </td>

                    <td class="money">
                        {{ $currency }}.
                        {{ number_format($subtotal, 2) }}
                    </td>
                </tr>


                <tr>
                    <td>
                        Impuestos
                    </td>

                    <td class="money">
                        {{ $currency }}.
                        {{ number_format($impuestos, 2) }}
                    </td>
                </tr>


                <tr>
                    <td>
                        Descuento (-)
                    </td>

                    <td class="money">
                        {{ $currency }}.
                        {{ number_format($descuento, 2) }}
                    </td>
                </tr>


                <tr>
                    <td>
                        Recargo (+)
                    </td>

                    <td class="money">
                        {{ $currency }}. 0.00
                    </td>
                </tr>


                <tr class="total-final">

                    <td>
                        Total factura
                    </td>

                    <td class="money">
                        {{ $currency }}.
                        {{ number_format($totalFactura, 2) }}
                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>



{{-- ============================================================= --}}
{{-- INFORMACIÓN DE OPERACIÓN Y PAGO --}}
{{-- ============================================================= --}}

<table class="payment-section">

    <tr>

        {{-- OPERACIÓN --}}
        <td class="operation-column">

            <div class="section-label">
                Tipo de operación
            </div>

            <div class="section-value">
                Venta
            </div>


            <br>


            <div class="section-label">
                Cantidad de productos
            </div>

            <div class="section-value">
                {{ number_format($totalCantidad, 2) }}
            </div>

        </td>


        {{-- PAGO --}}
        <td class="payment-column">

            <div class="section-label">
                Detalles de Pago
            </div>

            <div class="payment-line"></div>


            <div class="small-label">
                Forma de pago:
            </div>

            <div class="section-value">
                {{ $metodoPago ?? 'N/A' }}
            </div>


            <br>


            <div class="small-label">
                Efectivo recibido:
            </div>

            <div class="section-value">

                {{ $currency }}.
                {{ number_format($efectivoRecibido, 2) }}

            </div>


            <br>


            <div class="small-label">
                Cambio:
            </div>

            <div class="section-value">

                {{ $currency }}.
                {{ number_format($cambio, 2) }}

            </div>


            <br>


            <div class="small-label">
                Monto:
            </div>

            <div class="payment-total">

                {{ $currency }}.
                {{ number_format($totalFactura, 2) }}

            </div>

        </td>

    </tr>

</table>



{{-- ============================================================= --}}
{{-- REFERENCIA DEL DOCUMENTO --}}
{{-- ============================================================= --}}

<div class="document-reference">

    <strong>
        Documento generado por el sistema
        {{ $empresa->name ?? '' }}
    </strong>

    <br>

    Venta # {{ $getNextSaleNumber }}

    @if(!empty($customer['ref_page'] ?? null))

        |
        Referencia:
        {{ $customer['ref_page'] }}

    @endif

</div>



{{-- ============================================================= --}}
{{-- PIE --}}
{{-- ============================================================= --}}

<section class="footer">

    <table width="100%">

        <tr>

            <td width="33%">

                Sistema
                {{ $empresa->name ?? '' }}

            </td>


            <td
                width="34%"
                class="center"
            >

                Comprobante de Venta

            </td>


            <td
                width="33%"
                class="right"
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
