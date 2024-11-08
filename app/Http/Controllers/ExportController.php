<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
//use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Sale;
use Dompdf\Options;
use App\Exports\SaleExport;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
//use Hardevine\Cart\Facades\Cart;
use Gloudemans\Shoppingcart\Facades\Cart;

class ExportController extends Controller
{
    public $currentDate;

    public function __construct()
    {
        // Inicializa la fecha actual al crear la clase
        $this->currentDate = Carbon::now()->format('d-m-Y');
    }

    public function obtenerNombreVendedor($id)
    {
        $vendedor = User::find($id);
        return $vendedor ? $vendedor->name : 'No ingresado';
    }

    public function reportPDF($userId, $reportType, $dateFrom = null, $dateTo = null, $selectTipoEstado = null){

        $data = [];

        if($reportType == 0)  //VENTAS DEL DIA
        {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';

        }else{
            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($dateTo)->format('Y-m-d') . ' 23:59:59';
        }

        /*if($userId == 0){
            $data = Sale::join('users as u','u.id','sales.user_id')
                ->select('sales.*','u.name as user')
                ->whereBetween('sales.created_at', [$from, $to])
                ->get();
        }else{
            $data = Sale::join('users as u', 'u.id','sales.user_id')
                ->select('sales.*','u.name as user')
                ->whereBetween('sales.created_at', [$from, $to])
                ->where('user_id', $userId)
                ->get();
        }*/

        // Construir la consulta de ventas con el filtro de tipo de pago
        $query = Sale::join('users as u', 'u.id', 'sales.user_id')
            ->select('sales.*', 'u.name as user')
            ->whereBetween('sales.created_at', [$from, $to]);

        if ($userId != 0) {
            $query->where('user_id', $userId);
        }

        /*if ($selectTipoEstado !== null) {
            $query->where('sales.status', $selectTipoEstado);
        }*/

        if ($selectTipoEstado != 0) { // Si no es igual a 0, aplica el filtro
            $query->where('sales.status', $selectTipoEstado);
        }

        $data = $query->get();

        $user = $userId == 0 ? 'Todos' : User::find($userId)->name;

        foreach ($data as $item) {
            $item->seller_name = $this->obtenerNombreVendedor($item->seller);  // Usar la función para obtener el nombre del vendedor
        }

        $pdf = Pdf::loadView('pdf.reporte', compact('data', 'reportType','user','dateFrom','dateTo', 'selectTipoEstado'));
        //$pdf = PDF\Pdf::loadView('pdf.reporte', compact('data', 'reportType','user','dateFrom','dateTo'));

        return $pdf->stream("Reporte_{$this->currentDate}.pdf"); //visualizar
        //return $pdf->download('salesReport.pdf'); //descargar
    }

    public function reportVenta($seller, $getNextSaleNumber){

        $cart = Cart::content(); // Obtén los datos que deseas mostrar en el reporte

        // Generar el PDF con la vista y los datos
        $pdf = $this->generatePdf('pdf.impresionventa', [
            'cart' => $cart,
            'getNextSaleNumber' => $getNextSaleNumber,
            'seller' => $seller,
        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("Venta_{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }

    public function reportDetails($seller, $getNextSaleNumber){

        $cart = Cart::content(); // Obtén los datos que deseas mostrar en el reporte

        // Generar el PDF con la vista y los datos
        $pdf = $this->generatePdf('pdf.reportedetails', [
            'cart' => $cart,
            'getNextSaleNumber' => $getNextSaleNumber,
            'seller' => $seller,
        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("VentaDetails{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }

    public function reportBox($seller, $getNextSaleNumber){

        $cart = Cart::content(); // Obtén los datos que deseas mostrar en el reporte
        $sale = Sale::with('details')->find($getNextSaleNumber);
        //dd(Sale::with('details')->find($getNextSaleNumber));

        // Generar el PDF con la vista y los datos
        $pdf = $this->generatePdf('pdf.reportebox', [
            'cart' => $cart,
            'getNextSaleNumber' => $getNextSaleNumber,
            'details' => $sale->details,
            'seller' => $seller,
            'sale' => $sale
        ]);

        // Devolver el PDF como una respuesta de streaming
        return $pdf->stream("CloseBox{$getNextSaleNumber}_{$this->currentDate}.pdf");
    }

    private function generatePdf($view, $data)
    {
        $options = [
            'isHtml5ParserEnabled' => true,  // Habilita el análisis de HTML5
            'isRemoteEnabled' => true,       // Permite cargar recursos externos como imágenes
        ];

        return Pdf::loadView($view, $data)->setOptions($options);
    }



    public function reportExcel($userId, $reportType, $dateFrom = null, $dateTo = null, $selectTipoEstado = null)
    {
        /*$export = new SaleExport();
        $filePath = $export->reportExcel();

        // Crear una respuesta para descargar el archivo
        return response()->download($filePath)->deleteFileAfterSend(true);*/

        $export = new SaleExport($userId, $reportType, $dateFrom, $dateTo, $selectTipoEstado );
        $filePath = $export->reportExcel();

        // Descargar el archivo y eliminarlo después de la descarga
        return response()->download($filePath)->deleteFileAfterSend(true);

    }


}
