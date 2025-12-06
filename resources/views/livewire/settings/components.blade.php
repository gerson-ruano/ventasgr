<div class="flex justify-center items-start p-4">
    <div class="card w-full max-w-6xl bg-base-100 shadow-xl p-6 rounded-lg">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold mb-2">Bienvenido a tu Panel de Configuración</h1>
            <p class="text-blue-800 font-semibold">Selecciona un parámetro.</p>
        </div>
        <livewire:components.back-button/>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($meta as $setting)
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                        </span>
                    </label>

                    @switch($setting->type)
                        @case('boolean')
                            <div class="flex items-center gap-2">
                                <input type="checkbox" class="toggle"
                                       wire:model.lazy="settings.{{ $setting->key }}"
                                    @checked($settings[$setting->key] ?? false)>

                                <span class="text-sm font-medium">
                                    {{ ($settings[$setting->key] ?? false) ? 'Activado' : 'Desactivado' }}
                                </span>
                            </div>
                            @break

                        @case('number')
                            <input type="number" class="input input-bordered w-full" wire:model.lazy="settings.{{ $setting->key }}">
                            @break

                        @case('select')
                            <select class="select select-bordered w-full" wire:model.lazy="settings.{{ $setting->key }}">
                                @foreach ($setting->options_array as $option)
                                    <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                @endforeach
                            </select>
                            @break

                        {{--}}@case('image')
                        @case('file')
                            <input type="file" class="file-input file-input-bordered w-full"
                                   wire:model.lazy="settings.{{ $setting->key }}">
                            @break--}}

                        @default
                            <input type="text" class="input input-bordered w-full" wire:model.lazy="settings.{{ $setting->key }}">
                    @endswitch
                </div>
            @endforeach
        </div>

        <div class="flex justify-end mt-6 space-x-2">
            <button class="btn btn-outline ml-2" wire:click="resetToOriginal">Cancelar</button>
            <button class="btn btn-info" wire:click="save">Guardar Cambios</button>
        </div>
    </div>
</div>



