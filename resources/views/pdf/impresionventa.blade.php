<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Venta</title>
    {{--<link rel="stylesheet" href="{{ asset('css/custom_pdf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">--}}
    {{-- 👇 Lee el CSS del filesystem, no por URL --}}
    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
    </style>
</head>

<body>
{{--dd($data)--}}
<section class="header" style="top: -287px;">
    <table class="rounded-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td colspan="2" align="center">
                @include('livewire.components.empresa_header', ['empresa' => $empresa])
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <div class="flex items-center space-x-2" style="font-size: 16px;">
                    <strong>VENTA # {{ $getNextSaleNumber }}</strong>
                    <span class="status-message
                        {{ trim($statusMessage) === '"en proceso.."' ? 'text-blue' :
                            (trim($statusMessage) === '"pendiente de pago"' ? 'text-red' :
                            (trim($statusMessage) === '"anulada"' ? 'text-gray' : 'text-green')) }}">
                        {{ $statusMessage }}
                    </span>
                </div>
                @if($seller == 'Cliente')
                    <div class="mt-2" style="font-size: 14px;">
                        <span>Para: <strong>{{ $seller }}</strong></span>
                        @if(!empty($customer))
                            <div class="customer-info">
                                <div class="customer-columns">
                                    <div class="customer-column">
                                        <div class="customer-row">
                                            <span>Nombre: <strong>{{ $customer['name'] ?? 'N/A' }}</strong></span>
                                        </div>
                                        <div class="customer-row">
                                            <span>NIT: <strong>{{ $customer['nit'] ?? 'N/A' }}</strong></span>
                                        </div>
                                        <div class="customer-row">
                                            <span>Dirección: <strong>{{ $customer['address'] ?? 'N/A' }}</strong></span>
                                        </div>
                                        <div class="customer-row">
                                            <span>Departamento: <strong>{{ $customer['departamento_nombre'] ?? 'N/A' }}</strong></span>
                                        </div>
                                        <div class="customer-row">
                                            <span>Municipio: <strong>{{ $customer['municipio_nombre'] ?? 'N/A' }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="customer-column">
                                        <div class="customer-row">
                                            <span>Método de Pago: <strong>{{ $metodoPago ?? 'N/A' }}</strong></span>
                                        </div>
                                        <div class="customer-row">
                                            <span>Ref. de Pago: <strong>{{ $customer['ref_page'] ?? 'N/A' }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-2" style="font-size: 14px;">
                        <span>Vendedor: <strong>{{ $seller }}</strong></span>
                    </div>
                @endif
            </td>

    </table>
</section>

@if ($cart->isNotEmpty())
    <!-- Tabla de Carrito -->
    <table cellpadding="0" cellspacing="0" width="100%" class="table-items">
        <thead>
        <tr>
            <th align="center">#</th>
            <th align="center">Producto</th>
            <th align="center">Cantidad</th>
            <th>Precio Unitario</th>
            <th align="center">Precio</th>
            <th align="center">Imagen</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cart as $item)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">{{ $item->name }}</td>
                <td align="center">{{$item->qty}}</td>
                <td>{{$currency}}. {{ number_format($item->price, 2) }}</td>
                <td align="center">{{ $item->subtotal }}</td>
                <td align="center">
                    {{--@php
                        $imagePath = 'storage/products/' . $item->options->image;
                        $defaultImagePath = 'img/noimg.jpg';
                    @endphp

                    @if (is_file(public_path($imagePath)))
                        <img src="{{ asset($imagePath) }}" alt="Imagen del producto" height="20" width="20"
                             class="rounded">
                    @else
                        <img src="{{ asset($defaultImagePath) }}" alt="Imagen por defecto" height="20" width="20"
                             class="rounded">
                    @endif--}}
                    @php
                        $imagePath = public_path('storage/products/' . $item->options->image);
                        $defaultImagePath = public_path('img/noimg.jpg');
                    @endphp

                    @if (file_exists($imagePath))
                        <img src="{{ $imagePath }}" height="20" width="20" class="rounded">
                    @else
                        <img src="{{ $defaultImagePath }}" height="20" width="20" class="rounded">
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td align="center"><span><b>TOTALES:</b></span></td>
            <td colspan="1"></td>
            <td align="center" class="text-center"><strong>{{ $cart->sum('qty') }}</strong></td>
            <td><strong>{{$currency}}. {{ number_format($cart->sum('price'), 2) }}</strong></td>
            <td align="center">
                <strong>{{$currency}}. {{ number_format($cart->sum(function ($item) { return $item->price * $item->qty; }), 2) }}</strong>
            </td>
            <td colspan="1"></td>

        </tr>
        </tfoot>
    </table>
@else
    <!-- Tabla de Detalles de Venta -->
    <table class="table-items">
        <thead>
        <tr>
            <th>#</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($details as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->product->name }}</td>
                <td>{{ number_format($d->quantity, 2) }}</td>
                <td>{{$currency}}. {{ number_format($d->price, 2) }}</td>
                <td>{{$currency}}. {{ number_format($d->price * $d->quantity, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2"><strong>Totales:</strong></td>
            <td><strong>{{ number_format($details->sum('quantity'), 2) }}</strong></td>
            <td><strong>{{$currency}}. {{ number_format($details->sum('price'), 2) }}</strong></td>
            <td>
                <strong>{{$currency}}. {{ number_format($details->sum(function($d) { return $d->price * $d->quantity; }), 2) }}</strong>
            </td>
        </tr>
        </tfoot>
    </table>
@endif


<table class="table-items">
    <thead>
    <tr>
        <th>Subtotal</th>
        <th>Efectivo</th>
        <th>Cambio</th>
        <th>Descuento</th>
        <th>IVA</th>
        <th>Colaborador</th>
        <th>Fecha Ingreso</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$currency}}. {{ number_format(($efectivo - $change) - $discount - $totalTaxes, 2) }}</td>
        <td>{{$currency}}. {{ number_format($efectivo, 2) }}</td>
        <td>{{$currency}}. {{ number_format($change, 2) }}</td>
        <td>{{$currency}}. {{ number_format($discount, 2) }}</td>
        <td>{{$currency}}. {{ number_format($totalTaxes, 2) }}</td>
        <td>{{ $usuario->name }}</td>
        <td>{{ \Carbon\Carbon::now()->format('H:i:s d-m-Y') }}</td>
    </tr>
    </tbody>
</table>
<br>

<section class="footer table-items">
    <table cellpadding="0" cellspacing="0" class="rounded" width="100%">
        <tr>
            <td width="20%">
                <span>Sistema {{ $empresa->name }}</span>
            </td>
            <td width="60%" class="text-center">
                GR
            </td>
            <td class="text-center" width="20%">
                página <span class="pagenum"></span>
            </td>
        </tr>
    </table>
</section>
<script type="text/php">
    @include('pdf.partials.pdf_script')
</script>

</body>

</html>
