@php
    $index = 1; // Inicializamos el contador fuera del bucle
@endphp

<div class="grid flex-grow card bg-base-300 rounded-box place-items-center mb-1 ml-2 lg:mb-1 lg:ml-2 lg:mr-2">
    @if ($totalProduct = count($cart) > 0 )
        <!-- Table Section -->
        <div class="border overflow-x-auto bg-base-200 rounded-lg shadow-lg w-full mx-auto">
            <table class="hidden md:table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="title_table">#</th>
                    <th class="title_table">Imagen</th>
                    <th class="title_table">Nombre</th>
                    <th class="title_table">Precio</th>
                    <th class="title_table">Cantidad</th>
                    <th class="title_table">Subtotal</th>
                    <th class="title_table">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $item)
                    <tr class="bg-white dark:bg-gray-700 border-b dark:border-gray-600">
                        <td class="row_table">{{ $loop->iteration }}</td>
                        <td class="row_table">
                            <img
                                src="{{ asset(is_file(public_path('storage/products/' . $item->options->image)) ? 'storage/products/' . $item->options->image : 'img/noimg.jpg') }}"
                                class="rounded-lg h-12 w-12 object-cover mx-auto">
                        </td>
                        <td class="row_table">{{ $item->name }}</td>
                        <td class="row_table">Q.{{ number_format($item->price, 2) }}</td>
                        <td class="row_table">
                            <input type="number" wire:model="quantityInputs.{{ $item->id }}"
                                   wire:change="updateQty({{ $item->id }}, $event.target.value)"
                                   class="w-16 text-center bg-blue-100 border border-blue-500 rounded-lg text-sm"/>
                        </td>
                        <td class="row_table">Q.{{ number_format($item->price * $item->qty, 2) }}</td>
                        <td class="row_table">
                            <div class="flex space-x-2 justify-center">
                                <button wire:click.prevent="increaseQty({{ $item->id }})"
                                        class="btn btn-sm btn-success">
                                    <i class="fas fa-plus-square"></i>
                                </button>
                                <button wire:click.prevent="decreaseQty({{ $item->id }})" class="btn btn-sm">
                                    <i class="fas fa-minus-square"></i>
                                </button>
                                <button onclick="Confirm('{{ $item->id }}','este CARRITO','{{ $item->name }}')"
                                        class="btn btn-sm btn-error">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="hidden">
            <!-- Este contenido solo se muestra si hay productos -->
            <!-- Se puede usar para evitar mostrar la tabla y otros elementos cuando está vacía -->
        </div>
    @endif
</div>

<div class="md:hidden space-y-1">
    @foreach($cart as $item)
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 space-y-2">
            <div class="flex items-center space-x-4">
                <img
                    src="{{ asset(is_file(public_path('storage/products/' . $item->options->image)) ? 'storage/products/' . $item->options->image : 'img/noimg.jpg') }}"
                    class="h-16 w-16 rounded object-cover"/>
                <div>
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $item->name }}</h2>
                    <p class="text-gray-500 text-sm dark:text-gray-300">Q.{{ number_format($item->price, 2) }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Cantidad:</span>
                <input type="number" wire:model="quantityInputs.{{ $item->id }}"
                       wire:change="updateQty({{ $item->id }}, $event.target.value)"
                       class="w-20 text-center bg-blue-100 border border-blue-500 rounded-lg text-sm"/>
            </div>

            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Subtotal:</span>
                <span class="text-gray-800 font-bold dark:text-gray-100">
                    Q.{{ number_format($item->price * $item->qty, 2) }}
                </span>
            </div>

            <div class="flex justify-center space-x-2 mt-2">
                <button wire:click.prevent="increaseQty({{ $item->id }})" class="btn btn-sm btn-success">
                    <i class="fas fa-plus-square"></i>
                </button>
                <button wire:click.prevent="decreaseQty({{ $item->id }})" class="btn btn-sm">
                    <i class="fas fa-minus-square"></i>
                </button>
                <button onclick="Confirm('{{ $item->id }}','este CARRITO','{{ $item->name }}')"
                        class="btn btn-sm btn-error">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

@if (count($cart) === 0)
    <div class="flex items-center justify-center w-full mt-1 mb-1 mr-1 ml-2">
        <div role="alert"
             class="alert alert-warning text-center w-full max-w-lg p-6 bg-gradient-to-r from-yellow-200 via-yellow-300 to-yellow-400 rounded-lg shadow-lg transform transition-transform duration-300 ease-in-out scale-100 hover:scale-105">
            <i class="fas fa-info-circle text-2xl text-yellow-700"></i>
            <div class="mt-2">
                <h3 class="font-bold text-lg text-yellow-800">No hay productos en la venta!</h3>
                <p class="text-sm text-yellow-700">Agrega productos, ingresando el CODIGO respectivo ejem. #205</p>
            </div>
        </div>
    </div>
@endif

