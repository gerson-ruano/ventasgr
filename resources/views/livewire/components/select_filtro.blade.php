<div class="mt-0 mb-0 mr-2 ml-2 flex flex-col items-center">
    <label for="{{ $model }}_select"
           class="label-text text-center block text-sm font-medium mb-1">{{ $title }}</label>
    <select wire:model.live="{{ $model }}" id="{{ $model }}_select"
            class="select select select-info w-full">
        <option value="{{$val_default}}" selected>{{$default}}</option>
        @if($valores)
            @foreach ($valores as $valor)
                <option value="{{ $valor->id}}">{{ $valor->name}}</option>
            @endforeach
        @endif
    </select>

    @error($model) <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
</div>
