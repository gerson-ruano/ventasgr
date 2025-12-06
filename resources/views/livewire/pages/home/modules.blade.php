@extends('layouts.app')
@section('title', 'Home')
@section('content')
    @if(count($modules) > 0)
        <div class="text-center mt-8">
            <livewire:components.back-button route="{{ route('home') }}"/>
            <h1 class="text-2xl font-bold mb-2 text-gray-300 dark:text-gray-800">Bienvenido a tu Panel de Módulos</h1>
            <p class="text-gray-300 font-semibold mb-2">Selecciona un módulo para comenzar...</p>
        </div>
        <div class="flex justify-center items-center p-4">
            <div class="card w-full max-w-4xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-4">
                    @foreach($modules as $module)
                        <div class="relative group">
                            <div
                                class="card bg-base-100 text-gray-800 shadow-xl p-2 hover:shadow-md transition-shadow duration-300">
                                <div class="card-body text-center p-3">
                                    <a href="{{ $module['route'] }}"
                                       class="w-full flex flex-col items-center justify-center gap-2 px-3 py-4 rounded-lg bg-gray-100 hover:bg-gray-300 transition">
                                        <i class="{{ $module['icon'] }} text-2xl"></i>
                                        <span class="text-sm sm:text-base break-words text-wrap leading-tight text-gray-700">
                                        {{ $module['label'] }}
                                    </span>
                                    </a>
                                </div>
                            </div>
                            @if (!empty($module['description']))
                                <!-- CARD FLOTANTE EN HOVER -->
                                <div class="absolute hidden group-hover:block
                                    bg-gray-200 shadow-xl rounded-lg p-3
                                    w-60 z-50 left-1/2 -translate-x-1/2
                                    animate-fade-in text-sm text-dark-600">
                                    {{ $module['description'] }}
                                </div>
                            @endif
                        </div>

                        {{--}}<div class="card bg-base-100 shadow-md p-4">
                            <div class="card-body text-center">
                                <h2 class="card-title flex justify-center items-center gap-2">
                                    <a href="{{ $module['route'] }}"
                                       class="btn btn-xs sm:btn-sm md:btn-md lg:btn-lg xl:btn-xl">
                                        <i class="{{ $module['icon'] }}"></i> {{ $module['label'] }}
                                    </a>
                                </h2>

                                @if(!$module['has_children'])
                                    <a href="{{ $module['route'] }}" class="btn btn-ghost btn-info btn-sm mt-2">
                                        <i class="fa fa-arrow-right mr-1"></i> Acceder
                                    </a>
                                @endif
                            </div>
                        </div>--}}
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center justify-center min-h-screen px-4">
            <div
                class="card w-full max-w-md p-6 bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 rounded-lg shadow-lg text-center">
                <i class="fas fa-lock text-4xl text-yellow-700 mb-4"></i>
                <h3 class="text-lg font-bold text-yellow-800 mb-1">No tienes <strong>MODULOS</strong> asignados por el
                    momento</h3>
                <p class="text-sm text-yellow-700">Verifica con tu administrador o soporte.</p>
            </div>
        </div>
    @endif
@endsection


