<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

@php

    $labelColor = 'text-blue-500'; // Definir el color aquí
    $links = [
    'profile' => __('Perfil'),
    'home' => __('Inicio'),
    'categories' => __('Categorias'),
    'products' => __('Productos'),
    'coins' => __('Denominaciones'),
    'users' => __('Usuarios'),
    'roles' => __('Roles'),
    'permisos' => __('Permisos'),
    'asignar' => __('Asignar'),
    'pos' => __('Ventas'),
    'reports' => __('Reportes'),
    'cashout' => __('Cierre de Caja'),
    'graphics' => __('Estadistica'),
    'configuracion' => __('Configuración'),
    // Añade más rutas aquí según sea necesario
    ];
$routeExists = false;

    foreach ($links as $route => $label) {
        if (Route::has($route)) {
            $routeExists = true;
            break;
        }
    }

    if (!$routeExists) {
        abort(404);
    }
@endphp

<nav x-data="{ open: false }"
     class="menu bg-base-200 dark:bg-gray-500 border-b border-gray-100 dark:border-gray-700 dark:text-gray-500">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-4">
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                {{--<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Home') }}
                </x-nav-link>
            </div>--}}
                <div class="space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @foreach ($links as $route => $label)
                        @if (Route::has($route))
                            {{-- Verificar si la ruta existe --}}
                            @if (request()->routeIs($route))
                                <x-nav-link :href="route($route)" :active="true" wire:navigate
                                            class="font-medium !text-blue-600 dark:text-white">
                                    {{ $label }}
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route($route)" wire:navigate class="hidden">
                                    {{ $label }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endforeach
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium
                            rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300
                            focus:outline-none transition ease-in-out duration-150">
                                {{--<div x-data="{{ json_encode(['name' => auth()->user()->name, 'profile' => auth()->user()->profile]) }}"
                                x-text="name" x-text="profile"
                                x-on:profile-updated.window="name = $event.detail.name; profile = $event.detail.profile">
                                </div>--}}

                                @auth
                                    <div x-data="{
                            name: '{{ auth()->user()->name }}',
                            profile: {{ auth()->user()->profile ? json_encode(auth()->user()->profile) : '{}' }} }">
                                        <p x-text="name"></p>
                                        {{--}}<template x-if="Object.keys(profile).length !== 0">
                                            <p x-text="'' + profile"></p>
                                        </template>
                                        <template x-if="Object.keys(profile).length === 0">
                                            <p>Perfil no configurado</p>
                                        </template>--}}
                                    </div>
                                @endauth


                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">

                            <x-dropdown-link :href="route('users')" wire:navigate> {{--profile--}}
                                {{ __('Mi Perfil') }}
                            </x-dropdown-link>

                            <!-- Account Management -->
                            @auth
                                <div x-data="{
                            name: '{{ auth()->user()->name }}',
                            profile: {{ auth()->user()->profile ? json_encode(auth()->user()->profile) : '{}' }} }">
                                    {{--}}<p x-text="name"></p>--}}
                                    <template x-if="Object.keys(profile).length !== 0">
                                        <p x-text="'' + profile" class="ml-4 mb-1"></p>
                                    </template>
                                    <template x-if="Object.keys(profile).length === 0">
                                        <p>Perfil no configurado</p>
                                    </template>
                                </div>
                            @endauth

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Salir') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                  stroke-linecap="round"
                                  stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                  stroke-linecap="round"
                                  stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

            </div>
            <div class="flex items-end items-right">
                <div class="sm:-my-px sm:ms-10 sm:flex float-right">
                    <label class="flex cursor-pointer gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"/>
                            <path
                                d="M12 1v2M12 21v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M1 12h2M21 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/>
                        </svg>
                        <input type="checkbox" value="light" class="toggle theme-controller" id="themeToggle"
                        @auth
                            {{ auth()->user()->tema == 0 ? 'checked' : '' }} />
                        @endauth
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </label>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-4 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('pos')" :active="request()->routeIs('pos')" wire:navigate>
                        {{ __('Ventas') }}
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->

                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        @auth
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200"
                                 x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                 x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        @endauth

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile')" wire:navigate>
                                {{ __('Mi Perfil') }}
                            </x-responsive-nav-link>


                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-responsive-nav-link>
                                    {{ __('Salir') }}
                                </x-responsive-nav-link>
                            </button>
                        </div>
                    </div>
            </div>
</nav>
