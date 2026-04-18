
@if($isModalOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50 overflow-y-auto">
        {{-- Fondo opaco --}}
        <div class="fixed inset-0 bg-slate-600 bg-opacity-50 dark:bg-black dark:bg-opacity-70"></div>

        {{-- Contenedor del modal --}}
        <div class="bg-white dark:bg-gray-600 p-8 rounded-lg shadow-lg z-10 w-full max-w-4xl mx-4 sm:w-11/12 md:w-3/4 lg:w-1/2 xl:w-1/3">
            <h2 class="text-lg font-semibold mb-4 text-center text-gray-800 dark:text-gray-100">
                {{ $customer_data ? 'Editar Cliente' : 'Nuevo Cliente' }}
            </h2>

            <form wire:submit.prevent="{{ $customer_data ? 'updateCustomer' : 'saveCustomer' }}">

                {{-- Fila 1 --}}
                <div class="flex flex-col sm:flex-row sm:space-x-2 w-full mb-2 items-center">
                    <div class="w-full sm:w-1/2 mb-2 sm:mb-0 text-label">
                        @include('livewire.components.select_filtro', [
                            'default' => 'Seleccionar',
                            'val_default' => 0,
                            'title' => 'Metodo de Pago',
                            'model' => 'customer_method_page',
                            'valores' => $pagos,
                        ])
                    </div>

                    <div class="w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-center">Nombre</label>
                        <input id="customer_name" type="text" placeholder="Nombre del cliente"
                               class="input input-bordered input-info w-full mt-1 dark:bg-gray-900 dark:text-white dark:border-gray-600"
                               wire:model.live="customer_name">
                        @error('customer_name') <span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Fila 2 --}}
                <div class="flex flex-col sm:flex-row sm:space-x-2 w-full mb-2 items-center">
                    <div class="w-full sm:w-1/2 mb-2 sm:mb-0">
                        <label for="customer_nit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-center">NIT</label>
                        <input id="customer_nit" type="text" placeholder="Ingrese NIT"
                               class="input input-bordered input-info w-full mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               wire:model.live="customer_nit">
                        @error('customer_nit') <span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="w-full sm:w-1/2">
                        <label for="customer_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-center">Dirección</label>
                        <input id="customer_address" type="text" placeholder="Dirección del cliente"
                               class="input input-bordered input-info w-full mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               wire:model.live="customer_address">
                        @error('customer_address') <span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Departamento -->
                    <div>
                        @include('livewire.components.select_filtro', [
                            'model' => 'customer_departamento_id',
                            'title' => 'Departamento',
                            'default' => 'Seleccionar Departamento',
                            'val_default' => '',
                            'valores' => $departamentos,
                            'id_campo' => 'id',
                            'nombre_campo' => 'nombre',
                        ])
                    </div>

                    <!-- Municipio -->
                    <div>
                        @include('livewire.components.select_filtro', [
                            'model' => 'customer_municipio_id',
                            'title' => 'Municipio',
                            'default' => 'Seleccionar Municipio',
                            'val_default' => '',
                            'valores' => $municipios,
                            'id_campo' => 'id',
                            'nombre_campo' => 'nombre',
                            'disabled' => !$customer_departamento_id,
                        ])
                    </div>
                </div>

                {{-- Referencia de pago --}}
                @if ($customer_method_page > 1 && $customer_method_page < 4 )
                    <div class="w-full text-center mt-2">
                        <label for="ref_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referencia de Pago</label>
                        <input id="ref_page" type="text" placeholder="referencia de pago"
                               class="input input-bordered input-info w-full mt-1 sm:max-w-xs dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               wire:model="customer_ref_page">
                        @error('customer_ref_page') <span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                @endif

                {{-- Botones --}}
                <div class="flex justify-center mt-4 flex-wrap gap-2">
                    <button type="button" class="btn btn-default dark:border-gray-500 dark:text-gray-200" wire:click="closeModal">Cancelar</button>
                    <button type="submit" class="btn {{ $customer_data ? 'btn-info' : 'btn-success' }}">
                        {{ $customer_data ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
