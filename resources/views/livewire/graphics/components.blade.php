@extends('layouts.app')
{{--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}

@section('content')
    <div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 p-2">
            <!-- Card 1 -->
            <div class="card shadow-md bg-gray-500 text-white border border-blue-300 rounded-md p-2 flex flex-col items-center justify-center">
                <div class="flex items-center">
                    <div class="text-xl">
                        <i class="fas fa-money-bill-alt"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xs font-bold">Activo</h2>
                        <p class="text-sm">{{ number_format($totalMoney[1] ?? 0, 2) }} Q</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card shadow-md bg-gray-500 text-white border border-blue-300 rounded-md p-2 flex flex-col items-center justify-center">
                <div class="flex items-center">
                    <div class="text-xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xs font-bold">Usuarios</h2>
                        <p class="text-sm">{{ $users }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card shadow-md bg-gray-500 text-white border border-blue-300 rounded-md p-2 flex flex-col items-center justify-center">
                <div class="flex items-center">
                    <div class="text-xl">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xs font-bold">Ventas</h2>
                        <p class="text-sm">{{ $totalSales }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card shadow-md bg-gray-500 text-white border border-blue-300 rounded-md p-2 flex flex-col items-center justify-center">
                <div class="flex items-center">
                    <div class="text-xl">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xs font-bold">Productos</h2>
                        <p class="text-sm">{{ $products }}</p>
                    </div>
                </div>
            </div>
        </div>



        <div class="container mx-auto text-center text-black mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $chartConfigs = [
                        [
                            'id' => 'chartUltimosDias',
                            'title' => 'reportes de ventas recientes',
                            'data' => $salesData,
                            'height' => 'h-[400px]',
                            'checkEmpty' => fn($data) => $data->isEmpty()
                        ],
                        [
                            'id' => 'chartStock',
                            'title' => 'productos con bajo stock',
                            'data' => $stockProducts,
                            'height' => 'h-[400px]',
                            'checkEmpty' => fn($data) => empty($data) || count($data) == 0
                        ],
                        [
                            'id' => 'chartProductTop',
                            'title' => 'productos más vendidos',
                            'data' => $productSales,
                            'height' => 'h-[350px]',
                            'checkEmpty' => fn($data) => empty($data) || count($data) == 0
                        ],
                        /*[
                            'id' => 'chartReport',
                            'title' => 'productos en el stock',
                            'data' => $totalStock,
                            'height' => 'h-[350px]',
                            'checkEmpty' => fn($data) => empty($data)
                        ],*/
                        [
                            'id' => 'chartTopUsers',
                            'title' => 'ventas de usuarios',
                            'data' => $TopUserData,
                            'height' => 'h-[400px]',
                            'checkEmpty' => fn($data) => $data->isEmpty()
                        ],
                        [
                            'id' => 'chartIngresos',
                            'title' => 'Ingresos',
                            'data' => $totalMoney,
                            'height' => 'h-[350px]',
                            'checkEmpty' => fn($data) => empty($data) || count($data) == 0
                        ],
                        [
                            'id' => 'chartTendenciaAnual',
                            'title' => 'ventas anuales',
                            'data' => $salesMonths,
                            'height' => 'h-[400px]',
                            'checkEmpty' => fn($data) => empty($data) || count($data) == 0
                        ],
                        [
                            'id' => 'chartTipoPago',
                            'title' => 'ventas Tipo Pago recientes',
                            'data' => $ventasTipoPago,
                            'height' => 'h-[400px]',
                            'checkEmpty' => fn($data) => empty($data) || count($data) == 0
                        ],
                    ];
                @endphp

                @foreach($chartConfigs as $chart)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        @if(!$chart['checkEmpty']($chart['data']))
                            <div class="mb-4 {{ $chart['height'] }}">
                                <canvas id="{{ $chart['id'] }}" class="w-full h-full"></canvas>
                            </div>
                        @else
                            <div class="alert alert-warning bg-yellow-100 text-yellow-700 p-3 rounded-lg {{ $chart['height'] }} flex items-center justify-center">
                                <div>
                                    <strong>No hay {{ $chart['title'] }}.</strong>
                                    <p>¡Agrega datos para comenzar a ver gráficos!</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Datos ocultos para las gráficas --}}
        @if(!$salesData->isEmpty())
            <div id="daysOfWeek" hidden>@json($salesData->pluck('date'))</div>
            <div id="salesData" hidden>@json($salesData->pluck('sales'))</div>
        @endif

        @if(!empty($datosDeVentas))
            <div id="datosDeVentas" hidden>@json($datosDeVentas)</div>
        @endif

        @if(!empty($stockProducts))
            <div id="productosConMenosExistencias" hidden>@json($stockProducts)</div>
        @endif

        @if(!empty($productSales))
            <div id="productData" hidden>@json($productSales)</div>
        @endif

        @if(!empty($totalStock))
            <div id="totalStock" hidden>@json($totalStock)</div>
        @endif

        @if(!empty($totalSales))
            <div id="totalSales" hidden>@json($totalSales)</div>
        @endif

        @if(!empty($totalMoney))
            <div id="totalMoney" hidden>@json($totalMoney)</div>
        @endif

        @if(!$TopUserData->isEmpty())
            <div id="top-user-data" hidden
                 data-user-names="{{ json_encode($TopUserData->pluck('user_name')->toArray()) }}"
                 data-sales-counts="{{ json_encode($TopUserData->pluck('sales_count')->toArray()) }}">
            </div>
        @endif

        @if(!empty($salesMonths))
            <div id="salesMonths" hidden>@json($salesMonths)</div>
        @endif

        @if(!empty($ventasTipoPago))
            <div id="ventasTipoPago" hidden>@json($ventasTipoPago)</div>
        @endif
    </div>
@endsection


