<div class="flex flex-col w-full lg:flex-row lg:space-y-0 mt-1">
    <div class="lg:w-3/4">
        <div class="">
            @include('livewire.pos.partials.impresion')
        </div>
        <div class="">
            @include('livewire.pos.partials.detail')
        </div>
    </div>

    {{--<div class="divider lg:divider-horizontal"></div>--}}
    <div class="lg:w-1/4">
        @include('livewire.pos.partials.filtros')

        @include('livewire.pos.partials.coins')

        @include('livewire.pos.partials.total')

        @include('livewire.pos.partials.form')

        {{--<div
            class="grid flex-grow h-32 card bg-base-300 rounded-box place-items-center mb-1 ml-2 lg:mb-2 lg:ml-0 lg:mr-2">
            Contenido de la quinta columna
        </div>--}}
    </div>
</div>

{{--<partials> src="{{ asset('js/keypress.js') }}"</partials>--}}
@include('livewire.pos.scripts.shortcuts')
@include('livewire.pos.scripts.general')
{{--}}@include('livewire.pos.scripts.departamentos')--}}
{{--}}@include('livewire.pos.scripts.scan')--}}
