<div>
    <!-- Header Section -->
    <div class="flex justify-center items-center mt-1 mb-1 mr-1 ml-1">
        <h4 class="font-bold text-2xl">
            {{ $componentName }} | {{ $pageTitle }}
        </h4>
        <button class="btn btn-accent ml-4" wire:click="openModal">Nuevo Usuario</button>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-7xl mx-auto">
        <table class="table-auto w-full">
            <thead class="bg-base-300 dark:bg-gray-800">
                <tr>
                    <th class="py-2 px-4 text-center">No.</th>
                    <th class="py-2 px-4 text-left">Telefono</th>
                    <th class="py-2 px-4 text-left">Correo Electronico</th>
                    <th class="py-2 px-4 text-left">Estado</th>
                    <th class="py-2 px-4 text-left">Perfil</th>
                    <th class="py-2 px-4 text-center">Imagen</th>
                    <th class="py-2 px-4 text-center">Acción</th>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr class="bg-white dark:bg-gray-700 border-b dark:border-gray-600">
                    <td class="py-2 px-4 text-center">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                    <td class="py-2 px-4 text-left">{{ $user->telefono }}</td>
                    <td class="py-2 px-4 text-left">{{ $user->email }}</td>
                    <td class="py-2 px-4 text-left">{{ $user->status }}</td>
                    <td class="py-2 px-4 text-left">{{ $user->perfil }}</td>
                    <td class="py-2 px-4 text-center">
                        <img src="{{ $user->imagen }}" alt="Imagen de {{ $user->name }}"
                            class="rounded h-20 w-20 object-cover mx-auto">
                    </td>
                    <td class="py-2 px-4 text-center">
                        <button class="btn btn-info mr-2" wire:click="edit({{ $user->id }})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline btn-danger"
                            onclick="Confirm('{{ $user->id }}','0','userOS','{{ $user->name }}')"
                            title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-base-100 dark:bg-gray-800">
                <tr>
                <th class="py-2 px-4 text-center">No.</th>
                    <th class="py-2 px-4 text-left">Telefono</th>
                    <th class="py-2 px-4 text-left">Correo Electronico</th>
                    <th class="py-2 px-4 text-left">Estado</th>
                    <th class="py-2 px-4 text-left">Perfil</th>
                    <th class="py-2 px-4 text-center">Imagen</th>
                    <th class="py-2 px-4 text-center">Acción</th>
                </tr>
            </tfoot>
        </table>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
    @include('livewire.users.form')
</div>