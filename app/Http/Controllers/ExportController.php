<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

//use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Sale;
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
        $data = [];

        if ($reportType == 0)  //VENTAS DEL DIA
        {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';

        } else {
            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($dateTo)->format('Y-m-d') . ' 23:59:59';
        }

        // Construir la consulta de ventas con el filtro de tipo de pago
        $query = Sale::join('users as u', 'u.id', 'sales.user_id')
            ->select('sales.*', 'u.name as user')
            ->whereBetween('sales.created_at', [$from, $to]);

        if ($userId != 0) {
            $query->where('user_id', $userId);
        }

        if ($selectTipoEstado != 0) { // Si no es igual a 0, aplica el filtro
            $query->where('sales.status', $selectTipoEstado);
        }

        $data = $query->get();
        //$data = $query->paginate($PerPage);


        $user = $userId == 0 ? 'Todos' : User::find($userId)->name;

        foreach ($data as $item) {
            $item->seller_name = getNameSeller($item->seller);  // Usar la función para obtener el nombre del vendedor
        }
        $empresa = $this->companyVentas();
        $usuario = $this->currentUser();
        $currency = $this->currency;

        $pdf = Pdf::loadView('pdf.reporte', compact('data', 'reportType', 'user', 'dateFrom', 'dateTo', 'selectTipoEstado', 'empresa','usuario','currency'));
        //$pdf = PDF\Pdf::loadView('pdf.reporte', compact('data', 'reportType','user','dateFrom','dateTo'));

        return $pdf->stream("Reporte_{$this->currentDate}.pdf"); //visualizar
        //return $pdf->download('salesReport.pdf'); //descargar
    }

    // REPORTE DE CIERRE DE CAJA GENERAL
    public function reportBoxGeneral($userid, $fromDate = null, $toDate = null, $currency = null)
    {

        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();

        $data = Sale::whereBetween('created_at', [$from, $to])
            ->where('status','Paid')
            ->where('user_id', $userid)
            ->get();


        $user = $userid == 0 ? 'Todos' : User::find($userid)->name;

        foreach ($data as $item) {
            $item->seller_name = getNameSeller($item->seller);  // Usar la función para obtener el nombre del vendedor
        }
        $empresa = $this->companyVentas();
        $usuario = $this->currentUser();
        $currency = $this->currency;

        $pdf = Pdf::loadView('pdf.reporteboxgeneral', compact('data', 'user', 'fromDate', 'toDate', 'empresa','usuario','currency'));
        //$pdf = PDF\Pdf::loadView('pdf.reporte', compact('data', 'reportType','user','dateFrom','dateTo'));

        return $pdf->stream("ReporteBox_{$this->currentDate}.pdf"); //visualizar
        //return $pdf->download('salesReport.pdf'); //descargar
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
    /*public function reportVenta($change, $efectivo, $seller, $getNextSaleNumber, $totalTaxes, $discount, $customer_data )
    {

        $cart = Cart::content(); // Obtén los datos que deseas mostrar en el reporte
        $statusMessage = getSaleStatusMessage($getNextSaleNumber); //Obtiene del HELPER los estados
        $sale = Sale::with('details')->find($getNextSaleNumber);
        $empresa = $this->companyVentas();
        $usuario = $this->currentUser();
        $customerData = json_decode(urldecode(request('customer_data')), true);
        $currency = $this->currency;

        if (!$sale) {
            // Si la venta no existe aun, asignamos un valor predeterminado para evitar null
            $sale = (object)[
                'details' => [], // Definir detalles como un array vacío si no hay detalles
            ];
        }

       $metodoPago = $this->obtenerMetodoPago($customerData['method_page'] ?? null);

        // Generar el PDF con la vista y los datos
        $pdf = $this->generatePdf('pdf.impresionventa', [
            'cart' => $cart,
            'getNextSaleNumber' => $getNextSaleNumber,
            'details' => $sale->details,
            'seller' => $seller,
            'usuario' => $usuario,
            'empresa' => $empresa,
            'statusMessage' => $statusMessage,
            'change' => $change,
            'efectivo' => $efectivo,
            'discount' => $discount,
            'totalTaxes' => $totalTaxes,
            'customer' => $customerData,
            'metodoPago' => $metodoPago,
            'currency' => $this->currency,
        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("Venta_{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }*/

    // REPORTE DE NUEVA VENTA - FORMATO TÉRMICO
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
                $data['cart']->count();

        } elseif (
            isset($data['details']) &&
            $data['details']->isNotEmpty()
        ) {

            $cantidadProductos =
                $data['details']->count();
        }


        /*
         * Altura aproximada del ticket.
         */
        $altoBase = 400;

        $altoProducto = 35;

        $altoTicket =
            $altoBase +
            (
                $cantidadProductos *
                $altoProducto
            );


        /*
         * Papel térmico de 80 mm.
         */
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
    public function reportDetails($seller, $getNextSaleNumber)
    {
        $sale = Sale::with('details', 'seller')->find($getNextSaleNumber);
        //dd($sale);

        if (!$sale) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

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
        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("VentaDetails{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }

    // REPORTE DE CIERRE DE VENTA - DETALLES
    public function reportBox($seller, $getNextSaleNumber)
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
        $cart = Cart::content();

        $statusMessage = getSaleStatusMessage(
            $getNextSaleNumber
        );

        $sale = Sale::with([
            'details.product'
        ])->find($getNextSaleNumber);

        $empresa = $this->companyVentas();

        $usuario = $this->currentUser();

        $customerData = json_decode(
            urldecode($customer_data),
            true
        );

        /*
         * Si por algún motivo customer_data
         * no contiene JSON válido.
         */
        if (!is_array($customerData)) {
            $customerData = [];
        }

        /*
         * Si la venta aún no existe,
         * dejamos details como Collection
         * para poder usar isNotEmpty(), sum(), etc.
         */
        if (!$sale) {

            $sale = (object) [
                'details' => collect()
            ];
        }

        $metodoPago = $this->obtenerMetodoPago(
            $customerData['method_page'] ?? null
        );

        return [

            'cart' => $cart,

            'getNextSaleNumber' => $getNextSaleNumber,

            'details' => $sale->details,

            'seller' => $seller,

            'usuario' => $usuario,

            'empresa' => $empresa,

            'statusMessage' => $statusMessage,

            'change' => $change,

            'efectivo' => $efectivo,

            'discount' => $discount,

            'totalTaxes' => $totalTaxes,

            'customer' => $customerData,

            'metodoPago' => $metodoPago,

            'currency' => $this->currency,
        ];
    }

}
