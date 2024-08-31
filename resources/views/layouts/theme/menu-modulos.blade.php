<style>
details {
    position: relative;
    z-index: 4;
}

details summary {
    list-style: none;
}
</style>

<ul class="menu lg:menu-horizontal w-full bg-base-200 lg:mb-0">
    <li><a class="border dark:border-gray-200" href="{{url('pos')}}">
            <i class="fas fa-shopping-cart">
            </i>Venta</a></li>
    <li class="">
        <details class="">
            <summary class="border dark:border-gray-200 flex item-center">
                <i class="fas fa-project-diagram mr-1"></i>Gestion Stocks
            </summary>
            <ul class="mt-4">
                <li class="">
                    <a href="{{url('categories')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-tags mr-1"></i>Categorias
                    </a>
                </li>
                <li class="">
                    <a href="{{url('products')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-shopping-basket mr-1"></i>Productos
                    </a>
                </li>
                <li class="">
                    <a href="{{url('coins')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-money-bill-alt mr-1"></i>Monedas
                    </a>
                </li>
            </ul>
        </details>
    </li>
    <li class="">
        <details>
            <summary class="border dark:border-gray-200 flex item-center">
                <i class="fas fa-print mr-1"></i>Reporteria
            </summary>
            <ul>
                <li class="">
                    <a href="{{url('cashout')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-cash-register mr-1"></i>Cierre de caja
                    </a>
                </li>
                <li class="">
                    <a href="{{url('reports')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-file-contract mr-1"></i>Ventas
                    </a>
                </li>
                <li class="">
                    <a href="{{url('pos')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-chart-bar mr-1"></i>Estadistica
                    </a>
                </li>
            </ul>
        </details>
    </li>
    <li class="ml-1">
        <details>
            <summary class="border dark:border-gray-200 flex item-center">
                <i class="fas fa-users mr-1"></i>Gestion Usuarios
            </summary>
            <ul>
                <li class="">
                    <a href="{{url('roles')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-street-view mr-1"></i>Roles
                    </a>
                </li>
                <li class="">
                    <a href="{{url('permisos')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-unlock-alt mr-1"></i>Permisos
                    </a>
                </li>
                <li class="">
                    <a href="{{url('asignar')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-check-square mr-1"></i>Asignar
                    </a>
                </li>
                <li class="">
                    <a href="{{url('users')}}" class="border dark:border-gray-200 flex items-center">
                        <i class="fas fa-user mr-1"></i>Usuarios
                    </a>
                </li>
            </ul>
        </details>
    </li>
    <li class="">
        <div>
            {{--@include('partials.search')
            <livewire:components.searchbox :model="'search'" />--}}
        </div>
    </li>
    <li>
        <livewire:layout.navigation />
    </li>
    <li class="ml-auto">
        <label class="flex cursor-pointer gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5" />
                <path
                    d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" />
            </svg>
            <input type="checkbox" value="dark" class="toggle theme-controller" id="themeToggle" />
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </label>
    </li>
</ul>
