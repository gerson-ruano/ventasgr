<div
    class="flex flex-col sm:flex-row flex-wrap gap-4 justify-center items-center lg:items-start lg:justify-start
    h-auto sm:h-30 sm:mb-1 card bg-base-300 rounded-box mb-1 ml-2 lg:mb-1 lg:ml-0 lg:mr-2">
    <div class="flex flex-row flex-wrap items-center gap-2 w-full mb-2 mt-1">
        <!-- Caja de búsqueda -->
        <div class="flex-grow">
            <livewire:components.searchbox :placeholder="'Ingrese Código Producto'" :emitOnEnter="'scan-code'"/>
        </div>
    </div>

    {{--<div class="flex flex-col items-stretch mr-2 ml-2 mt-1 w-full">
    </div>--}}

    <div class="flex flex-row space-x-2 w-full mb-2">
        <div class="w-1/2">
            @include('livewire.components.select_filtro', [
                'default' => 'Elegir',
                'val_default' => 0,
                'title' => 'Estado de Pago',
                'model' => 'tipoPago',
                'valores' => $valores
            ])
        </div>

        <div class="w-1/2">
            @include('livewire.components.select_filtro', [
                'default' => 'Cliente',
                'val_default' => 0,
                'title' => 'Vendedor/Cliente',
                'model' => 'vendedorSeleccionado',
                'valores' => $vendedores,
            ])
        </div>
    </div>

</div>
