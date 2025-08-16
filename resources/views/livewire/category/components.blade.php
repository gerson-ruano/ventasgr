<div>
    <!-- Header Section -->
    <div class="flex justify-center items-center mt-1 mb-1 mr-1 ml-1">
        <div class="mr-2">
            <livewire:components.searchbox :model="'search'"/>
        </div>
        <h4 class="font-bold text-2xl mr-2 dark:text-gray-400">
            {{ $componentName }} | {{ $pageTitle }}
        </h4>
        @include('livewire.components.button_add', ['color' => 'accent' ,'model' => 'openModal', 'icon' => 'plus', 'title' => $componentName])
    </div>
    <!-- Table Section -->
    @if (count($categories) > 0)
        <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-5xl mx-auto">
            <table class="table_custom">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Descripcion</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $index => $category)
                    <tr class="bg-white dark:bg-gray-700 border-b dark:border-gray-600">
                        <td>
                            {{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <img src="{{ $category->imagen }}" alt="Imagen de {{ $category->name }}"
                                 class="rounded-lg h-12 w-12 object-cover mx-auto">
                        </td>
                        <td>
                            <div class="flex flex-col sm:flex-row items-center justify-center">
                                <button class="btn btn-sm btn-info mr-0 sm:mr-2 mb-2 sm:mb-0"
                                        wire:click="edit({{ $category->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline btn-danger"
                                        onclick="Confirm('{{ $category->id }}','{{ $componentName }}','{{ $category->name }}','{{ $category->products->count() }}','PRODUCTOS')"
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
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    @else
        @include('livewire.components.no-results', ['result' => $categories ,'name' => $componentName])
    @endif
    @include('livewire.category.form')
</div>

