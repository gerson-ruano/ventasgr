<div>
    <!-- Header Section -->
    <div class="flex justify-center items-center mt-1 mb-1 mr-1 ml-1">
        <div class="mr-2">
            <livewire:components.searchbox :model="'search'"/>
        </div>
        <h4 class="font-bold text-2xl">
            {{ $componentName }} | {{ $pageTitle }}
        </h4>
        @include('livewire.components.button_add', ['color' => 'accent' ,'model' => 'openModal','icon' => 'plus', 'title' => $componentName])
    </div>

    <!-- Table Section -->
    @if (count($permisos) > 0)
        <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-5xl mx-auto">
            <table class="table-auto w-full">
                <thead class="bg-base-300 dark:bg-gray-800">
                <tr>
                    <th class="title_table">No.</th>
                    <th class="title_table">Descripción</th>
                    <th class="title_table">Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($permisos as $index => $permiso)
                    <tr class="bg-white dark:bg-gray-700 border-b dark:border-gray-600">
                        <td class="row_table">
                            {{ ($permisos->currentPage() - 1) * $permisos->perPage() + $index + 1 }}</td>
                        <td class="row_table">{{ $permiso->name }}</td>
                        <td class="row_table">
                            <div class="flex flex-col sm:flex-row items-center justify-center">
                                <button class="btn btn-sm btn-info mr-0 sm:mr-2 mb-2 sm:mb-0"
                                        wire:click="edit({{ $permiso->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline btn-danger"
                                        onclick="Confirm('{{ $permiso->id }}','{{ $componentName }}','{{ $permiso->name }}')"
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
                    <th class="title_table">No.</th>
                    <th class="title_table">Descripción</th>
                    <th class="title_table">Acción</th>
                </tr>
                </tfoot>
            </table>
            <div class="mt-4">
                {{ $permisos->links() }}
            </div>
        </div>
    @else
        <!-- Mostrar notificación cuando no hay resultados -->
        @include('livewire.components.no-results', ['result' => $permisos ,'name' => $componentName])
    @endif
    @include('livewire.permisos.form')
</div>
