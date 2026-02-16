<div class="grid flex-grow card bg-base-300 rounded-box place-items-center mb-1 ml-2 lg:mb-1 lg:ml-2 lg:mr-2">
    @if ($sale = count($sales) > 0 )
        <!-- Table Section -->
        <div class="border overflow-x-auto bg-base-200 rounded-lg shadow-lg w-full mx-auto">
            <table class="table_custom">
                <thead>
                <h1 class="font-bold text-lg text-center">CIERRE DE CAJA</h1>

                <tr>
                    <th>No.</th>
                    <th>Venta</th>
                    <th>Total</th>
                    <th>Cantidad</th>
                    <th>Cliente/Vendedor</th>
                    <th>Fecha y Hora</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                {{--dd($sales)--}}

                @foreach($sales as $index => $item)
                    <tr">
                        <td>
                            {{ ($sales->currentPage() - 1) * $sales->perPage() + $index + 1 }}</td>
                        <td>
                            <h6>{{ $item->id }}</h6>
                        </td>
                        <td>{{$currency}}. {{number_format($item->total,2)}}</td>
                        <td>
                            <h6>{{ $item->items }}</h6>
                        </td>
                        <td>
                            <h6>{{getNameSeller($item->seller)}}</h6>
                        </td>
                        <td>
                            <h6>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}</h6>
                        </td>
                        <td>
                            <div class="flex flex-row items-center justify-center space-x-2">
                                <button wire:click.prevent="viewDetails({{$item}})" title="Detalles"
                                        class="btn btn-sm btn-outline btn-accent btn-i">
                                    <i class="fas fa-indent"></i>
                                </button>
                            </div>
                        </td>
                        {{--<td class="py-2 px-4 text-center">
                            <div class="flex flex-row items-center justify-center space-x-2">
                                <button wire:click.prevent="Edit({{$item->id}})" title="Editar"
                                        class="btn btn-sm btn-outline btn-success btn-i">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-outline btn-error"
                                        onclick="Confirm('{{ $item->id }}','este CARRITO','{{ $item->name }}')"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
                {{--<tfoot class="bg-base-100 dark:bg-gray-800">
                    <tr>
                        <th class="py-2 px-4 text-center">No.</th>
                        <th class="py-2 px-4 text-left">Descripción</th>
                        <th class="py-2 px-4 text-center">Imagen</th>
                        <th class="py-2 px-4 text-center">Acción</th>
                    </tr>
                </tfoot>--}}
            </table>
            <div class="mt-4">
                {{ $sales->links() }}
            </div>
            @include('livewire.cashout.partials.form')

        </div>
    @else
        <div class="hidden">
            <!-- Este contenido solo se muestra si hay detalles -->
            <!-- Se puede usar para evitar mostrar la tabla y otros elementos cuando está vacía -->
        </div>
    @endif
</div>
@if (count($sales) === 0 && count($details) === 0)
    <div class="flex items-center justify-center w-full mt-4">
        <div role="alert"
             class="alert alert-warning text-center w-full max-w-lg p-6 bg-gradient-to-r from-yellow-200 via-yellow-300 to-yellow-400 rounded-lg shadow-lg transform transition-transform duration-300 ease-in-out scale-100 hover:scale-105">
            <i class="fas fa-info-circle text-2xl text-yellow-700"></i>
            <div class="mt-2">
                <h3 class="font-bold text-lg text-yellow-800">Ninguna venta mostrada!</h3>
                <p class="text-sm text-yellow-700">Selecciona algun filtro de busqueda a tu izquierda</p>
            </div>
        </div>
    </div>
@endif
