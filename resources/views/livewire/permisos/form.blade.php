@if($isModalOpen)
<div class="fixed inset-0 flex items-center justify-center z-50">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
    <div class="bg-white p-8 rounded-lg shadow-lg z-10 w-full max-w-lg mx-4">
        <h2 class="text-lg font-semibold mb-4 text-center">
            {{ $selected_id ? 'Editar Permiso' : 'Nuevo Permiso' }}
        </h2>

        <form wire:submit="{{ $selected_id ? 'update' : 'store' }}">
            <div class="mb-4">
                <label for="category_name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input id="category_name" type="text" placeholder="Ej. products.view"
                    class="input input-bordered input-info mt-1 w-full" wire:model.blur="permissionName" />
                @error('permissionName') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" class="btn btn-outline mr-2" wire:click="closeModal">Cancelar</button>
                <button type="submit" class="btn {{ $selected_id ? 'btn-info' : 'btn-success' }}">
                    {{ $selected_id ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif
