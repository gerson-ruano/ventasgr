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
    @if (count($users) > 0)
        <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-7xl mx-auto">
            <table class="table_custom">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Imagen</th>
                    <th>Usuario</th>
                    <th>Telefono</th>
                    <th>Correo Electronico</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Tema</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>
                            {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                        <td>
                            <img src="{{ $user->imagen }}" alt="Imagen de {{ $user->name }}"
                                 class="rounded-lg h-12 w-12 object-cover mx-auto">
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>@if($user->phone > 0)
                                <h6>{{ $user->phone }}</h6>
                            @else
                                <h6>Sin numero</h6>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><B>{{ $user->profile }}<B></td>
                        <td><span
                                class="badge {{ $user->status == 'Active' ? 'badge-success' : 'badge-warning'}} text-uppercase">{{ $user->status }}</span>
                        </td>
                        <td>
                            <span class="badge text-uppercase px-2 py-1 rounded text-sm font-semibold
                                {{ $user->tema == 1
                                    ? 'bg-gray-300 text-black border border-gray-700 dark:text-gray-800'
                                    : 'bg-gray-800 text-white border border-gray-500' }}">
                                {{ $user->tema == 1 ? 'LIGHT' : 'DARK' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex flex-col sm:flex-row items-center justify-center">
                                <button class="btn btn-sm btn-info mr-0 sm:mr-2 mb-2 sm:mb-0"
                                        wire:click="edit({{ $user->id }})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline btn-danger"
                                        onclick="Confirm('{{ $user->id }}','{{ $componentName }}','{{ $user->name }}','{{ $user->saleDetails?->count() ?? 0 }}','USUARIOS')"
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
                    <th>Imagen</th>
                    <th>Usuario</th>
                    <th>Telefono</th>
                    <th>Correo Electronico</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Tema</th>
                    <th>Acción</th>
                </tr>
                </tfoot>
            </table>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    @else
        <!-- Mostrar notificación cuando no hay resultados -->
        @include('livewire.components.no-results', ['result' => $users ,'name' => $componentName])
    @endif
    @include('livewire.users.form')
</div>
