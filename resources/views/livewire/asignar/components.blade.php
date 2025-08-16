<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-center sm:items-center mt-4 mb-1 mx-1 gap-2 sm:gap-4">

        <!-- Selector de Roles -->
        <div class="w-full sm:w-auto">
            <select wire:model.live="role" id="role_select" name="role_select"
                    class="select select-info w-full">
                <option value="Elegir" selected>== Seleccione el Rol ==</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error("role")
            <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Selector de Módulos -->
        <div class="w-full sm:w-auto">
            <select wire:model.live="moduleSelected" class="select select-info w-full">
                <option value="all">Todos los Módulos</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->description }}</option>
                @endforeach
            </select>
        </div>

        <!-- Título -->
        <h4 class="font-bold text-xl sm:text-2xl text-center sm:text-left">
            {{ $componentName }}
        </h4>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row sm:justify-center sm:items-center mt-4 mb-1 mx-1 gap-2 sm:gap-4">
            <!-- Bloque de botones -->
            <div class="w-full sm:w-auto">
                <div class="flex justify-center sm:justify-start gap-2">
                    @include('livewire.components.button_add', [
                        'color' => 'accent',
                        'model' => 'syncAllFromModule',
                        'icon' => 'check-circle',
                        'title' => 'Asignar Modulo'
                    ])
                    @include('livewire.components.button_add', [
                        'color' => 'primary',
                        'model' => 'SyncAll',
                        'icon' => 'universal-access',
                        'title' => 'Asignar Todos'
                    ])
                    @include('livewire.components.button_add', [
                        'color' => 'warning',
                        'model' => 'Removeall',
                        'icon' => 'times-circle',
                        'title' => 'Revocar Todos'
                    ])
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    @if (count($permisos) > 0)
        <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-7xl mx-auto">
            <table class="table_custom">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Módulo</th>
                    <th>Permiso</th>
                    <th>Asignar</th>
                    {{--}}<th class="text-lg font-medium py-2 px-4 text-center">Roles con el Permiso</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($permisos as $index => $permiso)
                    @php
                        $modulo = $permiso->modules->first()->name ?? 'N/A';
                        $isChecked = $permiso->checked ?? false;
                    @endphp
                    <tr>
                        <td>
                            {{ ($permisos->currentPage() - 1) * $permisos->perPage() + $index + 1 }}
                        </td>
                        <td>
                            <span class="badge {{ $isChecked ? 'badge-info' : 'badge-' }}">
                                {{ ucfirst($modulo) }}
                            </span>
                        </td>
                        <td>
                            <span class="label-text ml-1">{{ explode('.', $permiso->name)[1] ?? '' }}</span>
                        </td>
                        <td>
                            <div class="flex justify-center items-center">
                                <label class="cursor-pointer label">
                                    <input type="checkbox" class="checkbox checkbox-info"
                                           wire:change="syncPermiso($event.target.checked, '{{ $permiso->name }}')"
                                           value="{{ $permiso->id }}" {{ $isChecked ? 'checked' : '' }}>
                                </label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $permisos->links() }}
            </div>
        </div>
    @else
        @include('livewire.components.no-results', ['result' => $permisos ,'name' => $componentName])
    @endif
</div>
