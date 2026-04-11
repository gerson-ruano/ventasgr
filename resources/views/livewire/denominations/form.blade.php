@if($isModalOpen)
<div class="fixed inset-0 flex items-center justify-center z-50">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
    <div class="bg-white p-8 rounded-lg shadow-lg z-10 w-full max-w-lg mx-4">
        <h2 class="text-lg font-semibold mb-4 text-center">
            {{ $selected_id ? 'Editar Denominación' : 'Nueva Denominación' }}
        </h2>

        <form wire:submit="{{ $selected_id ? 'update' : 'store' }}">
            <div class="mb-4">
                <label for="select_type" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select wire:model.live="type" id="select_type" class="select select-info w-full">
                    <option value="Elegir" selected>Elegir</option>
                    <option value="BILLETE">Billete</option>
                    <option value="MONEDA">Moneda</option>
                    <option value="OTRO">Otro</option>
                </select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label for="value" class="block text-sm font-medium text-gray-700">Valor</label>
                <input id="value" type="number" step="any" placeholder="Ej. 10 o 0.25"
                    class="input input-bordered input-info mt-1 w-full" wire:model.blur="value" />
                @error('value') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>
                @if ($image)
                <div class="flex justify-center mb-2">
                    <img src="{{ $image->temporaryUrl() }}" alt="Imagen de {{ $type }}" class="h-32 w-32 object-cover">
                </div>
                @elseif ($imageUrl)
                <div class="flex justify-center mb-2">
                    <img src="{{ $imageUrl }}" alt="Imagen de {{ $type }}" class="h-32 w-32 object-cover">
                </div>
                @endif

                <input type="file" wire:model.live="image" id="image"
                    class="file-input file-input-bordered file-input-accent w-full mt-1">
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
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
