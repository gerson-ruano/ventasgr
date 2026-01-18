<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:justify-center sm:items-center mt-4 mb-1 mx-1 gap-2 sm:gap-4">
        <livewire:components.back-button/>

    <div class="overflow-x-auto bg-base-300 p-4 rounded-lg shadow-lg max-w-7xl mx-auto">
        <table class="table_custom">
            <thead>
            <tr class="text-sm text-gray-400">
                <th>Módulo</th>
                <th>Descripción</th>
                <th class="text-center w-32">Activo</th>
            </tr>
            </thead>

            <tbody>
            @foreach($tree as $parent)

                {{-- PADRE --}}
                <tr class="bg-base-200 border-l-4 border-info">
                    <td class="font-bold text-base">
                        <div class="flex items-center gap-3">
                            <i class="{{ $parent['icon'] }} text-lg text-default"></i>
                            {{ $parent['label'] }}
                        </div>
                    </td>

                    <td class="text-sm text-gray-500">
                        {{ $parent['description'] }}
                    </td>

                    <td class="text-center">
                        <input type="checkbox"
                               class="toggle toggle-sm {{ $parent['active'] ? 'toggle-info' : 'toggle-error' }}"
                               wire:change="toggle('{{ $parent['key'] }}')"
                            {{ $parent['active'] ? 'checked' : '' }}>
                    </td>
                </tr>

                {{-- HIJOS --}}
                @foreach($parent['children'] as $child)
                    <tr class="hover:bg-base-100">
                        <td class="pl-12">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <span class="text-info">↳</span>
                                <i class="{{ $child['icon'] }}"></i>
                                {{ $child['label'] }}
                            </div>
                        </td>

                        <td class="text-xs text-gray-500">
                            {{ $child['description'] }}
                        </td>

                        <td class="text-center">
                            <input type="checkbox"
                                   class="toggle toggle-default toggle-sm"
                                   wire:change="toggle('{{ $child['key'] }}')"
                                {{ $child['active'] ? 'checked' : '' }}>
                        </td>
                    </tr>
                @endforeach

                {{-- separador --}}
                <tr>
                    <td colspan="3" class="h-2"></td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>


