<div>
    <!-- Header Section -->
    <div class="flex justify-center items-center mt-1 mb-1 mr-1 ml-1">
        <div class="mr-2">
            <livewire:components.searchbox :model="'search'"/>
        </div>
        <h4 class="font-bold text-2xl mr-2">
            {{ $componentName }} | {{ $pageTitle }}
        </h4>
        @include('livewire.components.button_add', ['color' => 'accent' ,'model' => 'openModal','icon' => 'plus', 'title' => $componentName])
    </div>
    <!-- Table Section -->
    @if (count($roles) > 0)
        <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-5xl mx-auto">
            <table class="table_custom">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $index => $rol)
                    <tr>
                        <td>
                            {{ ($roles->currentPage() - 1) * $roles->perPage() + $index + 1 }}</td>
                        <td>{{ $rol->name }}</td>
                        <td>
                            <div class="flex flex-col sm:flex-row items-center justify-center">
                                <button class="btn btn-sm btn-info mr-0 sm:mr-2 mb-2 sm:mb-0"
                                        wire:click="edit({{ $rol->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline btn-danger"
                                        onclick="Confirm('{{ $rol->id }}','{{ $componentName }}','{{ $rol->name }}')"
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
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>
                </tfoot>
            </table>
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    @else
        <!-- Mostrar notificación cuando no hay resultados -->
        @include('livewire.components.no-results', ['result' => $roles ,'name' => $componentName])
    @endif
    @include('livewire.roles.form')
</div>
