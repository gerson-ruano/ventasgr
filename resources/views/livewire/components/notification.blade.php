<div>
    @if ($visible)
        <div x-data="{ show: @entangle('visible') }"
             x-show="show"
             x-init="setTimeout(() => show = false, 2000)"
             x-transition:enter="transition ease-out duration-1500"
             x-transition:enter-start="translate-y-10 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-5000"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="-translate-y-[100vh] opacity-0"
             class="fixed bottom-4 right-4 z-50 w-80 max-w-xs alert shadow-lg"
             :class="{
        'alert-success': '{{ $type }}' === 'success',
        'alert-error': '{{ $type }}' === 'error',
        'alert-warning': '{{ $type }}' === 'warning',
        'alert-info': '{{ $type }}' === 'info',
        'alert-dark bg-gray-800 text-white border border-gray-700': '{{ $type }}' === 'dark'
    }">

            <div class="flex items-center gap-2">
                @if ($type === 'success')
                    <i class="fas fa-check-circle fa-lg text-gray-500"></i>
                @elseif ($type === 'error')
                    <i class="fas fa-times-circle fa-lg text-gray-500"></i>
                @elseif ($type === 'warning')
                    <i class="fas fa-exclamation-triangle fa-lg text-gray-500"></i>
                @elseif ($type === 'info')
                    <i class="fas fa-info-circle fa-lg text-gray-500"></i>
                @elseif ($type === 'dark')
                    <i class="fas fa-exclamation-circle fa-lg text-gray-500"></i>
                @endif
                <span>{{ $message }}</span>
            </div>
            <button @click="show = false" class="btn btn-sm btn-circle">✕</button>
        </div>
    @endif
</div>


