@extends('layouts.app')
@section('title', 'Module')
@section('content')

    <livewire:components.back-button route="{{ route('home') }}"/>

    @if(count($children) > 0)
        <div class="flex justify-center items-center p-4">
            <div class="card w-full max-w-4xl p-6">
                <div class="text-center mb-6 mt-4">
                    <h1 class="text-2xl font-bold mb-2 text-gray-300">Opciones de {{ $moduleLabel }}</h1>
                    <p class="text-gray-300 font-semibold mb-2">Selecciona una opción para comenzar...</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-6">
                    @foreach($children as $child)
                        <div class="relative group">
                            <div class="card bg-base-100 shadow-lg p-4">
                                <a href="{{ $child['route'] }}"
                                   class="w-full block text-center bg-gray-100 hover:bg-gray-400 transition-colors duration-300 rounded-lg px-4 py-3 text-gray-800">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="{{ $child['icon'] }} text-2xl"></i>
                                        <span class="text-sm sm:text-base break-words text-wrap leading-tight">
                                            {{ $child['label'] }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                            @if (!empty($child['description']))
                                <div class="absolute hidden group-hover:block
                                    bg-gray-200 shadow-xl rounded-lg p-3
                                    w-60 z-50 left-1/2 -translate-x-1/2
                                    animate-fade-in text-sm text-gray-700">
                                    {{ $child['description'] }}
                                </div>
                            @endif
                        </div>
                    @endforeach
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

