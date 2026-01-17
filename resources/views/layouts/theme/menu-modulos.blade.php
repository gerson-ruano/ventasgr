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

{{--}}
<ul class="menu lg:menu-horizontal w-full bg-base-200 lg:mb-0 z-50">
    <li class="">
        <a href="{{ url('home') }}"
            class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin|Seller|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
            <i class="fas fa-home mr-1"></i>Home
        </a>
    </li>
    <li>
        <details>
            <summary <a
                class="border dark:border-gray-200 {{ Auth::user()->hasRole('Admin|Seller|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                <i class="fas fa-shopping-cart"></i>Venta</a>
            </summary>

            <ul class="">
                <li class="">
                    <a href="{{ url('pos') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin|Seller|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-cart-plus mr-1"></i>Pos
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('api') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin|Seller') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-compress-alt mr-1"></i>Factus
                    </a>
                </li>
            </ul>
        </details>
    </li>

    <li>
        <details>
            <summary <a
                 class="border dark:border-gray-200 {{ Auth::user()->hasRole('Admin|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                <i class="fas fa-project-diagram"></i>Gestion Stocks</a>
            </summary>
            <ul class="">
                <li class="">
                    <a href="{{ url('categories') }}"
                        class="border dark:border-gray-200 {{ Auth::user()->hasRole('Admin|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-tags mr-1"></i>Categorias
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('products') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-shopping-basket mr-1"></i>Productos
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('coins') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-money-bill-alt mr-1"></i>Monedas
                    </a>
                </li>
            </ul>
        </details>
    </li>
    <li>
        <details>
            <summary
                class="border dark:border-gray-200 {{ Auth::user()->hasRole('Admin|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                <i class="fas fa-print mr-1"></i>Reporteria
            </summary>
            <ul>
                <li class="">
                    <a href="{{ url('cashout') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-cash-register mr-1"></i>Cierre
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('reports') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin|Employee') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-file-contract mr-1"></i>Reportes
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('graphics') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-chart-bar mr-1"></i>Estadistica
                    </a>
                </li>
            </ul>
        </details>
    </li>
    <li class="">
        <details>
            <summary
                class="border dark:border-gray-200 {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                <i class="fas fa-users mr-1"></i>Gestion Usuarios
            </summary>
            <ul>
                <li class="">
                    <a href="{{ url('roles') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-street-view mr-1"></i>Roles
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('permisos') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-unlock-alt mr-1"></i>Permisos
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('asignar') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-check-square mr-1"></i>Asignar Permisos
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('users') }}"
                        class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
                        <i class="fas fa-user mr-1"></i>Usuarios
                    </a>
                </li>
            </ul>
        </details>
    </li>
    <li class="">
        <a href="{{ url('configuracion') }}"
           class="border dark:border-gray-200 flex items-center {{ Auth::user()->hasRole('Admin') ? '' : 'opacity-50 pointer-events-none' }}">
            <i class="fas fa-cogs mr-1"></i>Configuración
        </a>
    </li>
    <li>
    </li>
    <li class="ml-auto">
        <livewire:layout.navigation />
    </li>
</ul>
--}}

<ul class="menu lg:menu-horizontal w-full bg-base-200 lg:mb-0 z-50">
    <li>
        <a href="{{ route('home') }}" class="flex items-center">
            <i class="fas fa-home mr-1"></i>Home
        </a>
    </li>
    @auth
        @foreach(getUserModules() as $key => $module)
            @if(isset($module['children']))
                <li>
                    <details>
                        <summary class="flex items-center cursor-pointer">
                            <i class="{{ $module['icon'] }} mr-1"></i>{{ $module['label'] }}
                        </summary>
                        <ul>
                            @foreach($module['children'] as $childKey => $child)
                                <li>
                                    <a href="{{ route($child['route']) }}" class="flex items-center">
                                        <i class="{{ $child['icon'] }} mr-1"></i>{{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>
            @else
                <li>
                    <a href="{{ route($module['route']) }}" class="flex items-center">
                        <i class="{{ $module['icon'] }} mr-1"></i>{{ $module['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    @endauth

    <li class="ml-auto">
        <livewire:layout.navigation/>
    </li>
</ul>
