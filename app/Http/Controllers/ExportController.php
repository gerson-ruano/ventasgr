<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

//use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Company;
use Dompdf\Options;
use App\Exports\SaleExport;
use App\Exports\SaleExportBox;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
//use Hardevine\Cart\Facades\Cart;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Traits\PagoTrait;

class ExportController extends Controller
{
    use PagoTrait;
    public $currentDate;
    public $currency = 'Q'; // Moneda por defecto

    public function __construct()
    {
        // Inicializa la fecha actual al crear la clase
        $this->currentDate = Carbon::now()->format('d-m-Y');
        $this->currency = setting('app_currency', 'Q');
    }

    // REPORTE DE VENTAS GENERAL
    public function reportPDF($userId, $reportType, $dateFrom = null, $dateTo = null, $selectTipoEstado = null)
    {
        /*
    |--------------------------------------------------------------------------
    | RANGO DE FECHAS
    |--------------------------------------------------------------------------
    */

        if ($reportType == 0) {

            /*
             * Ventas del día.
             */
            $from =
                Carbon::now()
                    ->startOfDay();


            $to =
                Carbon::now()
                    ->endOfDay();

        } else {

            /*
             * Ventas por rango.
             */
            $from =
                Carbon::parse(
                    $dateFrom
                )->startOfDay();


            $to =
                Carbon::parse(
                    $dateTo
                )->endOfDay();
        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        |
        | Cargamos también details.product para reutilizar
        | nuestra lógica común de descuentos.
        |
        */

        $query =
            Sale::with([
                'details.product'
            ])
                ->join(
                    'users as u',
                    'u.id',
                    '=',
                    'sales.user_id'
                )
                ->select(
                    'sales.*',
                    'u.name as user'
                )
                ->whereBetween(
                    'sales.created_at',
                    [
                        $from,
                        $to
                    ]
                );


        /*
         * Usuario específico.
         */
        if ($userId != 0) {

            $query->where(
                'sales.user_id',
                $userId
            );
        }


        /*
         * Estado específico.
         */
        if ($selectTipoEstado != 0) {

            $query->where(
                'sales.status',
                $selectTipoEstado
            );
        }


        /*
         * Ejecutar consulta.
         */
        $data =
            $query
                ->orderBy(
                    'sales.created_at',
                    'asc'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | VENDEDORES
        |--------------------------------------------------------------------------
        */

        foreach ($data as $item) {

            $item->seller_name =
                getNameSeller(
                    $item->seller
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PREPARAR VENTAS
        |--------------------------------------------------------------------------
        */

        $ventas =
            $this->prepararVentasReporte(
                $data
            );


        /*
        |--------------------------------------------------------------------------
        | RESUMEN GENERAL
        |--------------------------------------------------------------------------
        */

        $resumenGeneral =
            $this->calcularResumenGeneral(
                $ventas
            );


        /*
        |--------------------------------------------------------------------------
        | DATOS GENERALES
        |--------------------------------------------------------------------------
        */

        $user =
            $userId == 0
                ? 'Todos'
                : User::find($userId)->name;


        $empresa =
            $this->companyVentas();


        $usuario =
            $this->currentUser();


        $currency =
            $this->currency;


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'pdf.reporte',
                compact(
                    'data',
                    'ventas',
                    'resumenGeneral',
                    'reportType',
                    'user',
                    'dateFrom',
                    'dateTo',
                    'selectTipoEstado',
                    'empresa',
                    'usuario',
                    'currency'
                )
            );


        return $pdf->stream(
            "Reporte_{$this->currentDate}.pdf"
        );
    }

    // REPORTE DE CIERRE DE CAJA GENERAL
    // REPORTE DE CIERRE DE CAJA GENERAL
    public function reportBoxGeneral(
        $userid,
        $fromDate = null,
        $toDate = null,
        $currency = null
    ) {

        /*
        |--------------------------------------------------------------------------
        | RANGO DE FECHAS
        |--------------------------------------------------------------------------
        */

        $from =
            Carbon::parse(
                $fromDate
            )->startOfDay();


        $to =
            Carbon::parse(
                $toDate
            )->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        |
        | Se cargan details.product porque necesitamos
        | reconstruir los datos financieros de cada venta.
        |
        */

        $query =
            Sale::with([
                'details.product'
            ])
                ->join(
                    'users as u',
                    'u.id',
                    '=',
                    'sales.user_id'
                )
                ->select(
                    'sales.*',
                    'u.name as user'
                )
                ->whereBetween(
                    'sales.created_at',
                    [
                        $from,
                        $to
                    ]
                )
                ->where(
                    'sales.status',
                    'PAID'
                );


        /*
         * 0 significa todos los usuarios.
         */
        if ((int) $userid !== 0) {

            $query->where(
                'sales.user_id',
                $userid
            );
        }


        /*
         * Ejecutar consulta.
         */
        $data =
            $query
                ->orderBy(
                    'sales.created_at',
                    'asc'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | VENDEDOR
        |--------------------------------------------------------------------------
        */

        foreach ($data as $item) {

            $item->seller_name =
                getNameSeller(
                    $item->seller
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DATOS REUTILIZABLES
        |--------------------------------------------------------------------------
        */

        $ventas =
            $this->prepararVentasReporte(
                $data
            );


        $resumenGeneral =
            $this->calcularResumenGeneral(
                $ventas
            );


        /*
        |--------------------------------------------------------------------------
        | USUARIO CONSULTADO
        |--------------------------------------------------------------------------
        */

        $user =
            (int) $userid === 0
                ? 'Todos'
                : (
                User::find($userid)->name
                ?? 'N/A'
            );


        /*
        |--------------------------------------------------------------------------
        | DATOS GENERALES
        |--------------------------------------------------------------------------
        */

        $empresa =
            $this->companyVentas();


        $usuario =
            $this->currentUser();


        $currency =
            $this->currency;


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            Pdf::loadView(
                'pdf.reporteboxgeneral',
                compact(
                    'data',
                    'ventas',
                    'resumenGeneral',
                    'user',
                    'fromDate',
                    'toDate',
                    'empresa',
                    'usuario',
                    'currency'
                )
            );


        return $pdf->stream(
            "ReporteBox_{$this->currentDate}.pdf"
        );
    }

    // IMPRESION DE NUEVA VENTA
    public function reportVenta(
        $change,
        $efectivo,
        $seller,
        $getNextSaleNumber,
        $totalTaxes,
        $discount,
        $customer_data
    ) {

        $data = $this->getVentaReportData(
            $change,
            $efectivo,
            $seller,
            $getNextSaleNumber,
            $totalTaxes,
            $discount,
            $customer_data
        );


        $pdf = $this->generatePdf(
            'pdf.impresionventa',
            $data
        );


        return $pdf->stream(
            "Venta_{$getNextSaleNumber}_{$this->currentDate}.pdf"
        );
    }

    /* REPORTE DE NUEVA VENTA - FORMATO TÉRMICO*/
    public function reportVentaTermica(
        $change,
        $efectivo,
        $seller,
        $getNextSaleNumber,
        $totalTaxes,
        $discount,
        $customer_data
    ) {

        $data = $this->getVentaReportData(
            $change,
            $efectivo,
            $seller,
            $getNextSaleNumber,
            $totalTaxes,
            $discount,
            $customer_data
        );


        $pdf = $this->generatePdf(
            'pdf.impresion_termica',
            $data
        );


        /*
         * Cantidad de productos
         */
        $cantidadProductos = 0;


        if (
            isset($data['cart']) &&
            $data['cart']->isNotEmpty()
        ) {

            $cantidadProductos =
                $data['lineas']->count();

        } elseif (
            isset($data['details']) &&
            $data['details']->isNotEmpty()
        ) {

            $cantidadConDescuento =
                $data['lineas']
                    ->filter(function ($linea) {

                        return
                            $linea['descuento'] > 0;
                    })
                    ->count();
        }


        /*
         * Altura aproximada del ticket.
         */
        $altoBase = 400;

        $altoProducto = 35;

        $altoDescuento = 18;

        $altoTicket =
            $altoBase +
            (
                $cantidadProductos *
                $altoProducto
            ) +
            (
                $cantidadConDescuento *
                $altoDescuento
            );


        $pdf->setPaper([
            0,
            0,
            226.77,
            $altoTicket
        ]);


        return $pdf->stream(
            "Ticket_{$getNextSaleNumber}_{$this->currentDate}.pdf"
        );
    }

    // IMPRESION DE VENTA - DETALLES
    public function reportDetails(
        $seller,
        $getNextSaleNumber
    ) {

        /*
        |--------------------------------------------------------------------------
        | VENTA
        |--------------------------------------------------------------------------
        */

        $sale =
            Sale::with([
                'details.product',
                'seller'
            ])
                ->find(
                    $getNextSaleNumber
                );


        if (!$sale) {

            return response()->json(
                [
                    'error' =>
                        'Venta no encontrada'
                ],
                404
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATOS GENERALES
        |--------------------------------------------------------------------------
        */

        $statusMessage =
            getSaleStatusMessage(
                $getNextSaleNumber
            );


        $empresa =
            $this->companyVentas();


        $usuario =
            $this->currentUser();


        $seller_name =
            getNameSeller(
                $sale->seller
            );


        $customer =
            is_array($sale->customer_data)
                ? $sale->customer_data
                : [];


        $metodoPago =
            $this->obtenerMetodoPago(
                $customer['method_page']
                ?? null
            );


        /*
        |--------------------------------------------------------------------------
        | DATOS FINANCIEROS REUTILIZABLES
        |--------------------------------------------------------------------------
        */

        $lineas =
            $this->prepararDetallesVenta(
                $sale->details
            );


        $resumen =
            $this->calcularResumenDesdeLineas(
                $lineas,
                $sale->taxes ?? 0,
                $sale->total ?? null
            );


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf =
            $this->generatePdf(
                'pdf.reportedetails',
                [

                    'getNextSaleNumber' =>
                        $getNextSaleNumber,

                    /*
                     * Se conserva si alguna sección
                     * antigua todavía la utiliza.
                     */
                    'details' =>
                        $sale->details,

                    /*
                     * Datos comunes.
                     */
                    'lineas' =>
                        $lineas,

                    'resumen' =>
                        $resumen,

                    'seller' =>
                        $seller,

                    'seller_name' =>
                        $seller_name,

                    'usuario' =>
                        $usuario,

                    'sale' =>
                        $sale,

                    'statusMessage' =>
                        $statusMessage,

                    'empresa' =>
                        $empresa,

                    'customer' =>
                        $customer,

                    'metodoPago' =>
                        $metodoPago,

                    'currency' =>
                        $this->currency,
                ]
            );


        return $pdf->stream(
            "VentaDetails{$getNextSaleNumber}_{$this->currentDate}.pdf"
        );
    }
    /*public function reportDetails($seller, $getNextSaleNumber)
    {
        //$sale = Sale::with('details', 'seller')->find($getNextSaleNumber);
        $sale = Sale::with(['details.product', 'seller'])->find($getNextSaleNumber);
        //dd($sale);

        if (!$sale) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        $resumen = $this->calcularResumenVenta($sale);
        $lineas = $this->prepararDetallesVenta($sale->details);

        $statusMessage = getSaleStatusMessage($getNextSaleNumber);
        $empresa = $this->companyVentas();
        $usuario = $this->currentUser();

        $seller_name = getNameSeller($sale->seller); //Obtiene del HELPER los VENDEDORES
        $metodoPago = $this->obtenerMetodoPago($sale['customer_data']['method_page'] ?? null);
        //dd($metodoPago);

        // Generar el PDF con la vista y los datos
        $pdf = $this->generatePdf('pdf.reportedetails', [
            'getNextSaleNumber' => $getNextSaleNumber,
            'details' => $sale->details,
            'seller' => $seller,
            'seller_name' => $seller_name,
            'usuario' => $usuario,
            'sale' => $sale,
            'statusMessage' => $statusMessage,
            'empresa' => $empresa,
            'customer' => $sale->customer_data,
            'metodoPago' => $metodoPago,
            'currency' => $this->currency,
            'resumen' => $resumen,
            'lineas' => $lineas,

        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("VentaDetails{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }*/

    // REPORTE DE CIERRE DE VENTA - DETALLES
    /*public function reportBox($seller, $getNextSaleNumber)
    {

        $sale = Sale::with('details','seller')->find($getNextSaleNumber);
        //dd($sale);

        if (!$sale) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        $empresa = $this->companyVentas();
        $usuario = $this->currentUser();

        //$seller_name = $this->obtenerNombreVendedor($sale->seller);
        $seller_name = getNameSeller($sale->seller);
        $metodoPago = $this->obtenerMetodoPago($sale['customer_data']['method_page'] ?? null);

        // Generar el PDF con la vista y los datos
        $pdf = $this->generatePdf('pdf.reportebox', [
            'getNextSaleNumber' => $getNextSaleNumber,
            'details' => $sale->details,
            'seller' => $seller,
            'seller_name' => $seller_name,
            'usuario' => $usuario,
            'sale' => $sale,
            'empresa' => $empresa,
            'customer' => $sale->customer_data,
            'metodoPago' => $metodoPago,
            'currency' => $this->currency,
        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("CloseBox{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }*/

    public function reportBox(
        $seller,
        $getNextSaleNumber
    ) {

        $sale = Sale::with(['details.product', 'seller'])->find($getNextSaleNumber);

        if (!$sale) {

            return response()->json(
                [
                    'error' =>
                        'Venta no encontrada'
                ],
                404
            );
        }


        $empresa = $this->companyVentas();
        $usuario = $this->currentUser();

        $seller_name =
            getNameSeller(
                $sale->seller
            );

        $metodoPago =
            $this->obtenerMetodoPago(
                $sale['customer_data']['method_page']
                ?? null
            );


        /*
         * Datos financieros comunes.
         */
        $resumen =
            $this->calcularResumenVenta(
                $sale
            );


        /*
         * Detalles procesados.
         */
        $lineas =
            $this->prepararDetallesVenta(
                $sale->details
            );


        $pdf = $this->generatePdf(
            'pdf.reportebox',
            [

                'getNextSaleNumber' => $getNextSaleNumber,
                'details' => $sale->details,
                'lineas' => $lineas,
                'resumen' => $resumen,
                'seller' => $seller,
                'seller_name' => $seller_name,
                'usuario' => $usuario,
                'sale' => $sale,
                'empresa' => $empresa,
                'customer' => $sale->customer_data,
                'metodoPago' => $metodoPago,
                'currency' => $this->currency,
            ]
        );


        return $pdf->stream(
            "CloseBox{$getNextSaleNumber}_{$this->currentDate}.pdf"
        );
    }

    // FUNCION DE GENERAR PDF
    private function generatePdf($view, $data)
    {
        $options = [
            'isHtml5ParserEnabled' => true,  // Habilita el análisis de HTML5
            'isRemoteEnabled' => true,       // Permite cargar recursos externos como imágenes
        ];

        return Pdf::loadView($view, $data)->setOptions($options);
    }


    // REPORTE DE VENTAS EXCEL
    public function reportExcel($userId, $reportType, $dateFrom = null, $dateTo = null, $selectTipoEstado = null)
    {
        /*$export = new SaleExport();
        $filePath = $export->reportExcel();

        // Crear una respuesta para descargar el archivo
        return response()->download($filePath)->deleteFileAfterSend(true);*/

        $export = new SaleExport($userId, $reportType, $dateFrom, $dateTo, $selectTipoEstado);
        $filePath = $export->reportExcel();

        // Descargar el archivo y eliminarlo después de la descarga
        return response()->download($filePath)->deleteFileAfterSend(true);

    }

    // REPORTE DE CIERRE DE CAJA EXCEL
    public function reportBoxExcel($userid, $fromDate = null, $toDate = null)
    {

        $export = new SaleExportBox($userid, $fromDate, $toDate);
        $filePath = $export->reportExcel();

        // Descargar el archivo y eliminarlo después de la descarga
        return response()->download($filePath)->deleteFileAfterSend(true);

    }

    public function companyVentas()
    {
        $empresa = Company::first();

        if (!$empresa) {
            abort(404, 'No se encontró ninguna compañía');
        }
        return $empresa;
    }

    public function currentUser()
    {
        $currentUser = Auth::user();
        return $currentUser;
    }

    private function getVentaReportData(
        $change,
        $efectivo,
        $seller,
        $getNextSaleNumber,
        $totalTaxes,
        $discount,
        $customer_data
    ) {

        /*
        |--------------------------------------------------------------------------
        | DATOS GENERALES
        |--------------------------------------------------------------------------
        */

        $cart =
            Cart::content();


        $sale =
            Sale::with([
                'details.product'
            ])
                ->find(
                    $getNextSaleNumber
                );


        $empresa =
            $this->companyVentas();


        $usuario =
            $this->currentUser();


        $statusMessage =
            getSaleStatusMessage(
                $getNextSaleNumber
            );


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER DATA RECIBIDO
        |--------------------------------------------------------------------------
        */

        $customerData =
            json_decode(
                urldecode(
                    $customer_data
                ),
                true
            );


        if (!is_array($customerData)) {

            $customerData = [];
        }


        /*
        |--------------------------------------------------------------------------
        | VENTA YA GUARDADA
        |--------------------------------------------------------------------------
        */

        if ($sale) {

            /*
             * En una venta histórica preferimos
             * los datos almacenados en BD.
             */
            if (
                is_array(
                    $sale->customer_data
                )
            ) {

                $customerData =
                    $sale->customer_data;
            }


            $lineas =
                $this->prepararDetallesVenta(
                    $sale->details
                );


            $resumen =
                $this->calcularResumenDesdeLineas(
                    $lineas,
                    $sale->taxes ?? 0,
                    $sale->total ?? null
                );


            /*
             * Valores históricos reales.
             */
            $change =
                $sale->change ?? $change;

            $efectivo =
                $sale->cash ?? $efectivo;

            $totalTaxes =
                $resumen['impuestos'];

            $discount =
                $resumen['descuento'];


            $details =
                $sale->details;

        } else {

            /*
            |--------------------------------------------------------------------------
            | REPORTE DESDE CARRITO
            |--------------------------------------------------------------------------
            */

            $lineas =
                $this->prepararDetallesCarrito(
                    $cart
                );


            $resumen =
                $this->calcularResumenDesdeLineas(
                    $lineas,
                    $totalTaxes,
                    null
                );


            /*
             * Todavía no hay SaleDetail.
             */
            $details =
                collect();


            /*
             * Ya no dependemos del discount
             * recibido por URL.
             */
            $discount =
                $resumen['descuento'];
        }


        /*
        |--------------------------------------------------------------------------
        | MÉTODO DE PAGO
        |--------------------------------------------------------------------------
        */

        $metodoPago =
            $this->obtenerMetodoPago(
                $customerData['method_page']
                ?? null
            );


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA COMÚN
        |--------------------------------------------------------------------------
        */

        return [

            /*
             * Se mantienen por compatibilidad.
             */
            'cart' =>
                $cart,

            'details' =>
                $details,

            /*
             * Estos dos son los datos que
             * deben utilizar los PDF.
             */
            'lineas' =>
                $lineas,

            'resumen' =>
                $resumen,

            /*
             * Venta, cuando ya existe.
             */
            'sale' =>
                $sale,

            'getNextSaleNumber' =>
                $getNextSaleNumber,

            'seller' =>
                $seller,

            'usuario' =>
                $usuario,

            'empresa' =>
                $empresa,

            'statusMessage' =>
                $statusMessage,

            'change' =>
                (float) $change,

            'efectivo' =>
                (float) $efectivo,

            /*
             * Se conservan para no romper
             * vistas/rutas antiguas.
             */
            'discount' =>
                $resumen['descuento'],

            'totalTaxes' =>
                $resumen['impuestos'],

            'customer' =>
                $customerData,

            'metodoPago' =>
                $metodoPago,

            'currency' =>
                $this->currency,
        ];
    }

    private function calcularResumenVenta($sale)
    {
        $lineas =
            $this->prepararDetallesVenta(
                $sale->details
            );


        return $this->calcularResumenDesdeLineas(
            $lineas,
            $sale->taxes ?? 0,
            $sale->total ?? null
        );
    }

    private function prepararDetallesVenta($details)
    {
        return $details->map(function ($detail) {

            $cantidad =
                (float) $detail->quantity;

            $precio =
                (float) $detail->price;


            /*
             * Precio original antes del descuento.
             */
            $subtotal =
                $precio *
                $cantidad;


            /*
             * sale_details.discount contiene
             * el MONTO histórico descontado.
             */
            $descuento =
                (float) (
                    $detail->discount
                    ?? 0
                );


            $porcentajeDescuento =
                $subtotal > 0
                    ? (
                        $descuento /
                        $subtotal
                    ) * 100
                    : 0;


            $total =
                $subtotal -
                $descuento;


            return [

                /*
                 * Lo conservamos por compatibilidad
                 * con reportes que todavía lo utilizan.
                 */
                'detail' =>
                    $detail,

                'id' =>
                    $detail->id,

                'product_id' =>
                    $detail->product_id,

                'codigo' =>
                    $detail->product->barcode
                    ?? '-',

                'nombre' =>
                    $detail->product->name
                    ?? 'Producto',

                'cantidad' =>
                    $cantidad,

                'precio' =>
                    $precio,

                'subtotal' =>
                    round(
                        $subtotal,
                        2
                    ),

                'descuento' =>
                    round(
                        $descuento,
                        2
                    ),

                'porcentaje_descuento' =>
                    round(
                        $porcentajeDescuento,
                        2
                    ),

                'total' =>
                    round(
                        $total,
                        2
                    ),
            ];
        });
    }
    private function prepararDetallesCarrito($cart)
    {
        /*
         * Obtener productos en una sola consulta para
         * poder utilizar barcode y demás información.
         */
        $productIds =
            $cart
                ->pluck('id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->unique()
                ->values();


        $products =
            Product::whereIn(
                'id',
                $productIds
            )
                ->get()
                ->keyBy('id');


        return $cart->map(
            function ($item) use ($products) {

                $cantidad =
                    (float) $item->qty;

                $precio =
                    (float) $item->price;


                $subtotal =
                    $precio *
                    $cantidad;


                /*
                 * En el carrito el descuento
                 * está guardado como PORCENTAJE.
                 */
                $porcentajeDescuento =
                    (float) (
                        $item->options->discount
                        ?? 0
                    );


                $porcentajeDescuento =
                    min(
                        max(
                            $porcentajeDescuento,
                            0
                        ),
                        100
                    );


                $descuento =
                    $subtotal *
                    (
                        $porcentajeDescuento /
                        100
                    );


                $total =
                    $subtotal -
                    $descuento;


                $product =
                    $products->get(
                        (int) $item->id
                    );


                return [

                    'detail' =>
                        null,

                    'id' =>
                        null,

                    'product_id' =>
                        (int) $item->id,

                    'codigo' =>
                        $product->barcode
                        ?? '-',

                    'nombre' =>
                        $item->name,

                    'cantidad' =>
                        $cantidad,

                    'precio' =>
                        $precio,

                    'subtotal' =>
                        round(
                            $subtotal,
                            2
                        ),

                    'descuento' =>
                        round(
                            $descuento,
                            2
                        ),

                    'porcentaje_descuento' =>
                        round(
                            $porcentajeDescuento,
                            2
                        ),

                    'total' =>
                        round(
                            $total,
                            2
                        ),
                ];
            }
        );
    }

    private function calcularResumenDesdeLineas(
        $lineas,
        $impuestos = 0,
        $totalVentaHistorico = null
    ) {

        /*
         * Total original antes de descuento.
         */
        $valorBruto =
            (float) $lineas->sum(
                'subtotal'
            );


        /*
         * Suma monetaria de descuentos.
         */
        $descuento =
            (float) $lineas->sum(
                'descuento'
            );


        /*
         * Total calculado después de descuentos.
         */
        $totalCalculado =
            (float) $lineas->sum(
                'total'
            );


        /*
         * Para una venta guardada siempre
         * preferimos sales.total.
         *
         * Para carrito utilizamos el cálculo
         * de las líneas.
         */
        $totalVenta =
            $totalVentaHistorico !== null
                ? (float) $totalVentaHistorico
                : $totalCalculado;


        $impuestos =
            (float) $impuestos;


        /*
         * IVA incluido en el total.
         */
        $subtotal =
            $totalVenta -
            $impuestos;


        $totalCantidad =
            (float) $lineas->sum(
                'cantidad'
            );


        return [

            'valorBruto' =>
                round(
                    $valorBruto,
                    2
                ),

            'descuento' =>
                round(
                    $descuento,
                    2
                ),

            'subtotal' =>
                round(
                    $subtotal,
                    2
                ),

            'impuestos' =>
                round(
                    $impuestos,
                    2
                ),

            'totalVenta' =>
                round(
                    $totalVenta,
                    2
                ),

            'totalCantidad' =>
                $totalCantidad,
        ];
    }

    private function prepararVentasReporte($ventas)
    {
        return $ventas->map(function ($sale) {

            $lineas =
                $this->prepararDetallesVenta(
                    $sale->details
                );

            $resumen =
                $this->calcularResumenDesdeLineas(
                    $lineas,
                    $sale->taxes ?? 0,
                    $sale->total ?? null
                );

            return [
                'sale' => $sale,
                'lineas' => $lineas,
                'resumen' => $resumen,
            ];
        });
    }

    private function calcularResumenGeneral(
        $ventasPreparadas
    ) {

        /*
         * Cantidad de ventas encontradas.
         */
        $cantidadVentas =
            $ventasPreparadas->count();


        /*
         * Valor bruto acumulado.
         */
        $valorBruto =
            $ventasPreparadas->sum(
                function ($item) {

                    return
                        $item['resumen']['valorBruto'];
                }
            );


        /*
         * Descuentos acumulados.
         */
        $descuento =
            $ventasPreparadas->sum(
                function ($item) {

                    return
                        $item['resumen']['descuento'];
                }
            );


        /*
         * Subtotal sin IVA acumulado.
         */
        $subtotal =
            $ventasPreparadas->sum(
                function ($item) {

                    return
                        $item['resumen']['subtotal'];
                }
            );


        /*
         * IVA acumulado.
         */
        $impuestos =
            $ventasPreparadas->sum(
                function ($item) {

                    return
                        $item['resumen']['impuestos'];
                }
            );


        /*
         * Total final de todas las ventas.
         */
        $totalVenta =
            $ventasPreparadas->sum(
                function ($item) {

                    return
                        $item['resumen']['totalVenta'];
                }
            );


        /*
         * Cantidad total de artículos.
         */
        $totalCantidad =
            $ventasPreparadas->sum(
                function ($item) {

                    return
                        $item['resumen']['totalCantidad'];
                }
            );


        return [

            'cantidadVentas' =>
                $cantidadVentas,

            'valorBruto' =>
                round(
                    $valorBruto,
                    2
                ),

            'descuento' =>
                round(
                    $descuento,
                    2
                ),

            'subtotal' =>
                round(
                    $subtotal,
                    2
                ),

            'impuestos' =>
                round(
                    $impuestos,
                    2
                ),

            'totalVenta' =>
                round(
                    $totalVenta,
                    2
                ),

            'totalCantidad' =>
                $totalCantidad,
        ];
    }

}
