<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Venta</title>
    <link rel="stylesheet" href="{{ asset('css/custom_pdf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
</head>
@php
    $statusTranslations = [
        'PAID' => 'PAGADO',
        'CANCELLED' => 'ANULADO',
        'PENDING' => 'PENDIENTE'
    ];
@endphp

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
                    <strong>DETALLE DE VENTA #{{$getNextSaleNumber}}</strong>
                    <span
                        class="status-message {{ trim($statusMessage) === '"en proceso.."' ? 'text-blue' : (trim($statusMessage) === '"pendiente de pago"' ? 'text-red' : (trim($statusMessage) === '"anulada"' ? 'text-gray' : 'text-green')) }}">
                        {{ $statusMessage }}
                    </span>
                </span>
                @if($seller_name == 'Cliente')
                    <span style="font-size: 14px">Para:<strong>{{$seller_name}}</strong></span>
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
                @else
                    <span style="font-size: 14px">Vendedor: <strong>{{$seller_name}}</strong></span>
                @endif
            </td>

        </tr>
    </table>
</section>

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
        {{--dd($sale)--}}
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
<br>

<!--div class="total text-center"><br>
    <h6>Total: Q. {{-- number_format($details->sum(function($d) { return $d->price * $d->quantity; }), 2) --}}</h6>
</div-->

<table class="table-items">
    <thead>
    <tr>
        <th>Subtotal</th>
        <th>Efectivo</th>
        <th>Cambio</th>
        <th>Descuentos</th>
        <th>IVA</th>
        <th>Ingreso</th>
        <th>Modificado</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$currency}}. {{ number_format(($sale->cash - $sale->change) - ($sale->details->first()->discount ?? 0) - $sale->taxes, 2) }}</td>
        <td>{{$currency}}. {{ number_format($sale->cash, 2) }}</td>
        <td>{{$currency}}. {{ number_format($sale->change, 2) }}</td>
        <td>{{$currency}}. {{ ($sale->details->first()->discount ?? 0) }}</td>
        <td>{{$currency}}. {{ number_format($sale->taxes, 2) }}</td>
        {{--<td>{{ $statusTranslations[$sale->status] ?? $sale->status }}</td>--}}
        <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y H:i') }}</td>
        @if($sale->created_at != $sale->updated_at)
            <td>{{ \Carbon\Carbon::parse($sale->updated_at)->format('d-m-Y H:i') }}</td>
        @else
            <td>Sin cambios</td>
        @endif
    </tr>
    </tbody>
</table>
<br>

<div class="align">
    <span style="font-size: 10px">Usuario: <strong>{{ $usuario->name }}</strong></span>
    <span style="font-size: 10px">Fecha de Consulta: <strong>{{ \Carbon\Carbon::now()->format('H:i:s d-m-Y') }}</strong></span>
</div>

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
    @include('pdf.partials.pdf_script')
</script>

</body>

</html>

