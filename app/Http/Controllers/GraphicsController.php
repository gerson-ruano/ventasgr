<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GraphicsController extends Controller
{
    public function index()
    {
        $salesData = $this->ultimasVentas();
        $totalStock = $this->productoStock();
        $totalSales = $this->ventasTotales();

        $ingresosPorStatus = $this->ingresoTotalPorStatus();
        $totalMoney = $ingresosPorStatus->pluck('total_por_status')->toArray();
        $TopUserData = $this->TopUserVentas();
        $productSales = $this->productTop();
        $stockProducts = $this->productosConMenosExistencias();

        $stock = setting('low_stock', 10); // Valor por defecto

        if ($stockProducts->isNotEmpty() && is_numeric($stockProducts->first()->alerts)) {
            $stock = (int) $stockProducts->first()->alerts;
        }
        // Obtener las ventas con el stock determinado
        $datosDeVentas = $this->obtenerDatosDeVentas($stock);
        $ventasTipoPago = $this->obtenerDatosDeVentasTipoPago();
        $salesMonths = $this->TendenciaAnual();


        $users = $this->UsersTotales(); //usuarios del sistema
        $products = $this->ProductTotales(); //productos existentes


        return view('livewire.graphics.components', [
            'salesData' => $salesData,
            'totalStock' => $totalStock,
            'TopUserData' => $TopUserData,
            'totalSales' => $totalSales,
            'totalMoney' => $totalMoney,
            'productSales' => $productSales,
            'datosDeVentas' => $datosDeVentas,
            'stockProducts' => $stockProducts,
            'ventasTipoPago' => $ventasTipoPago,
            'salesMonths' => $salesMonths,
            'users' => $users,
            'products' => $products,
        ]);
    }

    public function productoStock(){
        //return Product::sum('stock');
        //return Product::pluck('stock')->toArray();
        $totalStock = [
            'labels' => ['Producto A', 'Producto B', 'Producto C', 'Producto D'],
            'data' => [150, 80, 200, 45]
        ];
        return $totalStock;
    }
    public function productTotales(){
        return Product::count();
    }
    public function ventasTotales(){
        $excludedStatuses = ['CANCELLED'];
        return Sale::whereNotIn('status', $excludedStatuses)->count();
    }
    public function UsersTotales(){
        return User::count();
    }

    public function ingresoTotalPorStatus() {
        return Sale::select('status', DB::raw('SUM(total) as total_por_status'))
            ->groupBy('status')
            ->get();
    }

    public function ultimasVentas(){
        $endDate = Carbon::now(); // Fecha actual
        $startDate = $endDate->copy()->subDays(30); // Fecha hace 30 días

        $salesData = Sale::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return $salesData;
    }

    public function productTop(){

        $productSales = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id') // Ajusta los nombres de las columnas según tu estructura
            ->select('products.name', DB::raw('SUM(sale_details.quantity) as total_quantity'))
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
        return $productSales;
    }

    public function ProductTop2()
    {
        $products = Product::select('name')
            ->selectRaw('COUNT(*) as total_sales')
            ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
            ->groupBy('products.name')
            ->orderByDesc('total_sales')
            ->take(5) // Obtener los 5 productos más vendidos
            ->get();

        return $products;
    }

    public function TopUserVentas(){
        $endDate = Carbon::now(); // Fecha actual
        $startDate = $endDate->copy()->subDays(90); // Fecha hace 30 días

        $TopUserData = Sale::whereDate('sales.created_at', '>=', $startDate)
            ->whereDate('sales.created_at', '<=', $endDate)
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->selectRaw('users.name as user_name, COUNT(*) as sales_count')
            ->groupBy('user_name')
            ->orderBy('user_name')
            ->get();
        return $TopUserData;
    }

    public function productosConMenosExistencias()
    {
        $products = Product::whereNotNull('stock')
            ->whereNotNull('alerts')
            ->whereColumn('stock', '<', 'alerts')
            ->get();

        // \Log::info('Productos con bajo stock encontrados: ' . $products->count());
        return $products;
    }

    public function obtenerDatosDeVentas($stock) {
        return Sale::where('sales.status', '!=', 'cancelled')  // ← Excluir ventas anuladas
        ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(sale_details.quantity) as total_quantity')
            )
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderBy('total_quantity', 'desc')
            ->get();

    }


    public function obtenerDatosDeVentasTipoPago() {
        return DB::table('sales')
            ->select('status', DB::raw('COUNT(id) as total_ventas'))
            ->groupBy('status')
            ->get();
    }

    public function TendenciaAnual(){
        // Obtener el año actual
        $currentYear = Carbon::now()->year;

        // Inicializar un arreglo para almacenar los datos de ventas por año
        $salesByYear = [];

        // Iterar sobre los últimos dos años
        for ($year = $currentYear - 1; $year <= $currentYear; $year++) {
            // Calcular las fechas de inicio y fin del año
            $startDate = Carbon::create($year, 1, 1)->startOfDay();
            $endDate = Carbon::create($year, 12, 31)->endOfDay();

            // Consultar las ventas para el año actual en el rango de fechas
            $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as sales')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Almacenar las ventas por mes para el año actual en el arreglo
            $salesByYear[$year] = $sales;
        }
        return $salesByYear;
    }
}
