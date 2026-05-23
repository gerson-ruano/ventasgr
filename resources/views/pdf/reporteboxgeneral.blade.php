<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    {{--}}<link rel="stylesheet" href="{{ asset('css/custom_pdf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">--}}
    <style>
        {!! file_get_contents(public_path('css/pdf.css')) !!}
    </style>
    <style>
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed; /* Hace que las celdas tengan un ancho fijo */
        }

        .table-items th,
        .table-items td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 4px; /* Reduce el padding para hacer las celdas más pequeñas */
            font-size: 12px; /* Reduce el tamaño de la fuente */
            word-wrap: break-word; /* Permite que el texto se ajuste dentro de la celda */
        }

        .table-items th {
            background-color: #f2f2f2;
            font-size: 12px; /* Reduce el tamaño de la fuente de los encabezados */
        }

        .table-items tbody tr:last-child td {
            border-bottom: 1px solid #ddd;
        }

        .footer {
            position: fixed;
            bottom: -20px; /* Espacio adicional para evitar montado */
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            line-height: 35px;
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .pagenum:before {
            content: counter(page);
        }

        body {
            counter-reset: page;
        }

        .invoice-logo {
            max-width: 80px; /* Ajusta el tamaño del logo según el espacio disponible */
            margin-bottom: 0;
        }
    </style>
</head>
@php
    $statusTranslations = [
        'PAID' => 'Pagado',
        'CANCELLED' => 'Anulado',
        'PENDING' => 'Pendiente'
    ];
@endphp

<body>
<section class="header" style="top: -287px;">
    <table cellpadding="0" cellspacing="0" width="100%">
        <section class="header" style="margin-bottom: 10px;">
            <h1 style="font-size: 24px; margin: 0; font-weight: bold; text-align: center;">{{ $empresa->name }}</h1>
            <table width="100%" cellpadding="0" cellspacing="0" style="text-align: left;">
                <tr>
                    <td width="20%" style="vertical-align: top; padding: 10px;">
                        {{--dd($empresa->imagen--}}

                        <img src="{{ $empresa->imagen_pdf }}" alt="Logo VentasGR" class="invoice-logo" style="max-width: 80px;">
                        {{--}}<img src="{{ public_path('img/ventasgr_logo.png') }}" alt="Logo VentasGR" class="invoice-logo" style="max-width: 80px;">--}}
                    </td>

                    <!--td colspan="2" style="padding: 10px; text-align: center;"-->
                    <td width="50%">

                            <strong>Cierre de Caja</strong><br>

                            Fechas: <strong>{{ $fromDate  }}</strong> al <strong>{{ $toDate }}</strong><br>

                            Fecha de consulta: <strong>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</strong>

                        <br>
                        Usuario:  <strong>{{ $user }}</strong>
                    </td>--}}
                    <td width="30%">
                        <div class="empresa-header" style="line-height: 1.2;">
                            <p style="font-size: 14px; margin: 0;">Dirección: <strong>{{ $empresa->address }}</strong></p>
                            <p style="font-size: 14px; margin: 0;">Nit: <strong>{{ $empresa->nit }}</strong></p>
                            <p style="font-size: 14px; margin: 0;">Email: <strong>{{ $empresa->email }}</strong></p>
                            <p style="font-size: 14px; margin: 0;">Teléfono: <strong>{{ $empresa->phone }}</strong></p>
                        </div>
                    </td>
                </tr>
            </table>
        </section>
        <tr>

        </tr>
    </table>
</section>

<section style="margin-top: 0px">
    <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
        <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="8%">VENTA</th>
            <th width="12%">IMPORTE</th>
            <th width="10%">CANTIDAD</th>
            <th width="10%">ESTADO</th>
            <th width="12%">CLIENTE</th>
            <th width="12%">USUARIO</th>
            <th width="16%">FECHA/HORA</th>
        </tr>
        </thead>
        <tbody>
        @php
            $firstPageCount = 35;  // Número de registros en la primera página
            $otherPagesCount = 42; // Número de registros en las páginas siguientes
        @endphp

        @foreach ($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->id }}</td>
                <td style="text-align: right;">{{ number_format($item->total, 2) }}</td>
                <td>{{ $item->items }}</td>
                <td>{{ $statusTranslations[$item->status] ?? $item->status }}</td>
                <td>{{ $item->seller_name }}</td>
                <td>{{ $user }}</td>
                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @if (($index + 1) == $firstPageCount && !$loop->last)
                <tr>
                    <td colspan="8" style="border: none; height: 1px;"></td>
                </tr>
                <section class="footer table-items">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="20%">Sistema {{ $empresa->name }}</td>
                            <td width="60%" class="text-center">GR</td>
                            <td width="20%" class="text-center">Pág. <span class="pagenum"></span></td>
                        </tr>
                    </table>
                </section>
                <tr class="page-break"></tr>
            @elseif ($index + 1 > $firstPageCount && ($index + 1 - $firstPageCount) % $otherPagesCount == 0 && !$loop->last)
                <tr>
                    <td colspan="8" style="border: none; height: 1px;"></td>
                </tr>
                <section class="footer table-items">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="20%">Sistema {{ $empresa->name }} </td>
                            <td width="60%" class="text-center">GR</td>
                            <td width="20%" class="text-center">Pág. <span class="pagenum"></span></td>
                        </tr>
                    </table>
                </section>
            <tr class="page-break"></tr>
            @endif
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2"><b>TOTALES:</b></td>
            <td style="text-align: right;"><b>{{$currency}}. {{ number_format($data->sum('total'), 2) }}</b></td>
            <td><b>{{ $data->sum('items') }}</b></td>
            <td colspan="4"></td>
        </tr>
        </tfoot>
    </table>
</section>
<div class="align">
    <span style="font-size: 10px">Usuario: <strong>{{ $usuario->name }}</strong></span>
    <span style="font-size: 10px">Fecha de Consulta: <strong>{{ \Carbon\Carbon::now()->format('H:i:s d-m-Y') }}</strong></span>
</div>
<section class="footer table-items">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td width="20%">Sistema {{ $empresa->name }}</td>
            <td width="60%" class="text-center">GR</td>
            <td width="20%" class="text-center">Pág. <span class="pagenum"></span></td>
        </tr>
    </table>
</section>
</body>
</html>



