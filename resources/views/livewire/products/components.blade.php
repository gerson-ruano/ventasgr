<div>
    <!-- Header Section -->
    <div class="flex justify-center items-center mt-1 mb-1 mr-1 ml-1">
        <div class="mr-2">
            <livewire:components.searchbox :model="'search'" />
        </div>
        <h4 class="font-bold text-2xl">
            {{ $componentName }} | {{ $pageTitle }}
        </h4>
        <div class="ml-2">
            @include('livewire.components.button_add', ['color' => 'accent' ,'model' => 'openModal', 'icon' => 'plus', 'title' => "Nuevo Producto"])


        </div>
    </div>

    <!-- Table Section -->
    @if (count($products) > 0)
    <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-7xl mx-auto">
        <table class="table_custom">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Barcode</th>
                    <th>Descripción</th>
                    <th>Categoria</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Inv Minimo</th>
                    <th>Imagen</th>
                    <th>Acción</th>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                <tr>
                    <td>
                        {{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>
                    <td>{{ $product->barcode }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->price }}</td>
                    {{--}}<td class="py-2 px-4 text-left">{{ $product->stock }}</td>--}}
                    <td><span
                            class="badge {{ $product->stock >= $product->alerts ? 'badge-success' : 'badge-warning'}} text-uppercase">{{ $product->stock }}</span>
                    </td>
                    <td>{{ $product->alerts }}</td>
                    <td>
                        <img src="{{ $product->imagen }}" alt="Imagen de {{ $product->name }}"
                            class="rounded-lg h-12 w-12 object-cover mx-auto">
                    </td>
                    <td>
                        <div class="flex flex-col sm:flex-row items-center justify-center">
                            @can('products.addstock')
                                <button class="btn btn-sm btn-success mr-0 sm:mr-2 mb-2 sm:mb-0"
                                        wire:click="openAddStockModal({{ $product->id }})">
                                    + Agregar Stock
                                </button>
                            @endcan
                            <button class="btn btn-sm btn-info mr-0 sm:mr-2 mb-2 sm:mb-0"
                                wire:click="edit({{ $product->id }})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline btn-danger"
                                onclick="Confirm('{{ $product->id }}','{{ $componentName}}','{{ $product->name }}')"
                                title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-base-100 dark:bg-gray-800">
                <tr>
                    <th>No.</th>
                    <th>Barcode</th>
                    <th>Descripción</th>
                    <th>Categoria</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Inv Minimo</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
            </tfoot>
        </table>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
    @else
        <!-- Mostrar notificación cuando no hay resultados -->
        @include('livewire.components.no-results', ['result' => $products ,'name' => $componentName])
    @endif
    @include('livewire.products.form')
</div>
