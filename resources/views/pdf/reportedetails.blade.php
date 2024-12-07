<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Venta</title>
    <link rel="stylesheet" href="{{ asset('css/custom_pdf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
</head>

<body>

<section class="header" style="top: -287px;">
    <table class="rounded-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td colspan="2" align="center">
                <!--span style="font-size: 25px; font-weight: bold;"> Sistema {{-- config('app.name') --}}</span-->
                @include('livewire.components.empresa_header', ['empresa' => $empresa])
            </td>
        </tr>
        <tr>
            <!--td width="30%" style="vertical-align: top; padding-top: 10px; padding-left: 30px; position: relative">
                <img src="{{-- public_path('img/ventasgr_logo.png') --}}" alt="Logo VentasGR" class="invoice-logo"
                     style="max-width: 100px;">
            </td-->
            <td colspan="2" style="padding: 5px; text-align: center;">
                <span style="font-size: 16px; display: flex; align-items: center;">
                    <strong>DETALLE DE VENTA</strong>
                    <span class="status-message {{ trim($statusMessage) === '"en proceso"' ? 'text-blue' : (trim($statusMessage) === '"pendiente de pago"' ? 'text-red' : (trim($statusMessage) === '"anulado"' ? 'text-gray' : 'text-green')) }}">
                        {{ $statusMessage }}
                    </span>
                </span>
                <span style="font-size: 16px"><strong>Venta # {{$getNextSaleNumber}}</strong></span>
                <br>
                <span style="font-size: 16px">Fecha de Consulta:<strong>{{ \Carbon\Carbon::now()->format('H:i:s d-m-Y') }}</strong></span>
                <br>
                <span style="font-size: 14px">Cliente: {{$seller}}</span>
            </td>

        </tr>
    </table>
</section>

<!--h3 aling="">Venta #</h3-->

<table class="table-items">
    <thead>
    <tr>
        <th>#</th>
        <th>Producto</th>
        <th>Precio Unitario</th>
        <th>Cantidad</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($details as $d)
        {{--dd($sale)--}}
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $d->product->name }}</td>
            <td>Q. {{ number_format($d->price, 2) }}</td>
            <td>{{ number_format($d->quantity, 2) }}</td>
            <td>Q. {{ number_format($d->price * $d->quantity, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2"><strong>Totales:</strong></td>
        <td><strong>Q. {{ number_format($details->sum('price'), 2) }}</strong></td>
        <td><strong>{{ number_format($details->sum('quantity'), 2) }}</strong></td>
        <td>
            <strong>Q. {{ number_format($details->sum(function($d) { return $d->price * $d->quantity; }), 2) }}</strong>
        </td>
    </tr>
    </tfoot>
</table>

<div class="total text-center"><br>
    <h6>Total: Q. {{ number_format($details->sum(function($d) { return $d->price * $d->quantity; }), 2) }}</h6>
</div>

<table class="table-items">
    <thead>
    <tr>
        <th>No.</th>
        <th>Cantidad</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Imagen</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cart as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->subtotal }}</td>
            <td>
                @php
                    $imagePath = 'storage/products/' . $item->options->image;
                    $defaultImagePath = 'img/noimg.jpg';
                @endphp

                @if (is_file(public_path($imagePath)))
                    <img src="{{ asset($imagePath) }}" alt="Imagen del producto" height="20" width="20"
                         class="rounded">
                @else
                    <img src="{{ asset($defaultImagePath) }}" alt="Imagen por defecto" height="20" width="20"
                         class="rounded">
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td><b>TOTALES:</b></td>
        <td><strong>{{ $cart->sum('qty') }}</strong></td>
        <td></td>
        <td>
            <strong>Q. {{ number_format($cart->sum(function($item) { return $item->price * $item->qty; }), 2) }}</strong>
        </td>
        <td></td>
    </tr>
    </tfoot>
</table>

<section class="footer table-items">
    <table cellpadding="0" cellspacing="0" class="" width="100%">
        <tr>
            <td width="20%">
                <span>Sistema {{ $empresa->name }}</span>
            </td>
            <td width="60%" class="text-center">
                GR
            </td>
            <td class="text-center" width="20%">
                pág. <span class="pagenum"></span>
            </td>
        </tr>
    </table>
</section>
<script type="text/php">
    if (isset($pdf)) {
            $pdf->page_script('
                if ($PAGE_COUNT > 1) {
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $size = 10;
                    $pageText = "Página: " . $PAGE_NUM . " de " . $PAGE_COUNT;
                    $y = 15;
                    $x = 520;
                    $pdf->text($x, $y, $pageText, $font, $size);
                }
            ');
        }
</script>

</body>

</html>

