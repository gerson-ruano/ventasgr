<table class="totales">

    <tr>

        <td>
            Subtotal:
        </td>

        <td class="right">

            {{ $currency }}.
            {{ number_format(
                $totalProductos,
                2
            ) }}

        </td>

    </tr>


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
