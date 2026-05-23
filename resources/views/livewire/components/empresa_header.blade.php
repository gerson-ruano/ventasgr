<section class="header" style="margin-bottom: 10px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="text-align: left;">
        <tr>
            <td width="20%" style="vertical-align: top;">
                {{--}}<img src="{{ asset('img/ventasgr_logo.png') }}" alt="Logo VentasGR" class="invoice-logo" style="max-width: 80px;">
                <img src="{{ $empresa->imagen }}" alt="Logo VentasGR" class="invoice-logo" style="max-width: 80px;">--}}
                @php
                    // Limpia la ruta por si trae "storage/" o URL completa
                    $imagenPath = public_path(str_replace('/storage/', 'storage/',
                                  parse_url($empresa->imagen, PHP_URL_PATH) ?? $empresa->imagen));
                @endphp

                @if($empresa->imagen && file_exists($imagenPath))
                    @php
                        $imageData = base64_encode(file_get_contents($imagenPath));
                        $imageMime = mime_content_type($imagenPath);
                    @endphp
                    <img src="data:{{ $imageMime }};base64,{{ $imageData }}"
                         alt="Logo VentasGR"
                         class="invoice-logo"
                         style="max-width: 80px;">
                @else
                    <span>{{ $empresa->name }}</span>
                @endif
            </td>
            <td width="80%">
                <div class="empresa-header" style="line-height: 1.2;">
                    <h1 style="font-size: 20px; margin: 0; font-weight: bold;">{{ $empresa->name }}</h1>
                    <p style="font-size: 12px; margin: 0;">{{ $empresa->address }}</p>
                    <p style="font-size: 12px; margin: 0;">Nit: {{ $empresa->nit }}</p>
                    <p style="font-size: 12px; margin: 0;">Teléfono: {{ $empresa->phone }}</p>
                    <p style="font-size: 12px; margin: 0;">Email: {{ $empresa->email }}</p>
                </div>
            </td>
        </tr>
    </table>
</section>

<style>
    .empresa-header h1 {
        font-size: 20px;
        font-weight: bold;
        margin: 0;
        line-height: 1.5;
    }

    .empresa-header p {
        font-size: 12px;
        margin: 0;
        line-height: 1.2;
    }

    .invoice-logo {
        max-width: 80px; /* Ajusta el tamaño del logo según el espacio disponible */
        margin-bottom: 0;
    }
</style>

