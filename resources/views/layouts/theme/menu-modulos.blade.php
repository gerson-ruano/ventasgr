<style>
    details > summary::-webkit-details-marker {
        display: none;
    }

    details[open] > ul {
        margin-top: 0 !important;
        padding-top: 0 !important;
        z-index: 50;
    }

    details summary {
        list-style: none;
    }
</style>

<ul class="menu lg:menu-horizontal w-full bg-base-200 lg:mb-0 z-50">

    <li>
        <a href="{{ route('home') }}" class="flex items-center">
            <i class="fas fa-home mr-1"></i> Home
        </a>
    </li>

    @auth
        @foreach(getUserModules() as $module)

            {{-- MÓDULO CON HIJOS --}}
            @if($module['has_children'])
                <li>
                    <details>
                        <summary class="flex items-center cursor-pointer">
                            <i class="{{ $module['icon'] }} mr-1"></i>
                            {{ $module['label'] }}
                        </summary>

                        <ul>
                            @foreach($module['children'] as $child)
                                <li>
                                    <a href="{{ route($child['route']) }}" class="flex items-center">
                                        <i class="{{ $child['icon'] }} mr-1"></i>
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>

                {{-- MÓDULO SIN HIJOS --}}
            @elseif(!empty($module['route']))
                <li>
                    <a href="{{ $module['route'] }}" class="flex items-center">
                        <i class="{{ $module['icon'] }} mr-1"></i>
                        {{ $module['label'] }}
                    </a>
                </li>
            @endif

        @endforeach
    @endauth

    <li class="ml-auto">
        <livewire:layout.navigation/>
    </li>

</ul>

