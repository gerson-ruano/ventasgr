@if($isModalOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
        <div class="bg-white p-8 rounded-lg shadow-lg z-10 w-full max-w-lg mx-4">
            <h2 class="text-lg font-semibold mb-4 text-center">
                {{ $selected_id ? 'Editar Compañia' : 'Nueva Compañia' }}
            </h2>

            <form wire:submit="{{ $selected_id ? 'update' : 'store' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="category_name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input id="category_name" type="text" placeholder="Ej. Cursos"
                               class="input input-bordered input-info mt-1 w-full" wire:model.blur="name"/>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_address" class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input id="category_address" type="text" placeholder="Ej. 3ra calle 10ma avenida Zona 3"
                               class="input input-bordered input-info mt-1 w-full" wire:model.blur="address"/>
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_email" class="block text-sm font-medium text-gray-700">Correo
                            electronico</label>
                        <input id="category_email" type="email" placeholder="Ej. usuario@email.com"
                               class="input input-bordered input-info mt-1 w-full" wire:model.blur="email"/>
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_phone" class="block text-sm font-medium text-gray-700">Telefono</label>
                        <input id="category_phone" type="phone" placeholder="Ej. 12341234"
                               class="input input-bordered input-info mt-1 w-full" wire:model.blur="phone"/>
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_nit" class="block text-sm font-medium text-gray-700">NIT</label>
                        <input id="category_nit" type="text" placeholder="Ej. 1234567"
                               class="input input-bordered input-info mt-1 w-full" wire:model.blur="nit"/>
                        @error('nit') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>
                        @if ($image)
                            <div class="flex justify-center mb-2">
                                <img src="{{ $image->temporaryUrl() }}" alt="Imagen de {{ $name }}"
                                     class="h-32 w-32 object-cover">
                            </div>
                        @elseif ($imageUrl)
                            <div class="flex justify-center mb-2">
                                <img src="{{ $imageUrl }}" alt="Imagen de {{ $name }}" class="h-32 w-32 object-cover">
                            </div>
                        @endif

                        <input type="file" wire:model.live="image" id="image"
                               class="file-input file-input-bordered file-input-accent w-full mt-1">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
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
