@extends('layouts.app')
@section('title', 'Module')
@section('content')

    <livewire:components.back-button route="{{ route('home') }}"/>

    @if(count($children) > 0)
        <div class="flex justify-center items-center p-4">
            <div class="card w-full max-w-4xl p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($children as $child)
                        <div class="relative group">
                        <div class="card bg-base-100 shadow-xl relative group">
                            <div class="card-body text-center">
                                @if(!empty($child['route']))
                                    <a href="{{ route($child['route']) }}"
                                       class="flex flex-col items-center gap-2 p-4 rounded-lg bg-gray-100 hover:bg-gray-300 transition">
                                        <i class="{{ $child['icon'] }} text-2xl"></i>
                                        <span>{{ $child['label'] }}</span>
                                    </a>
                                @else
                                    <div class="flex flex-col items-center gap-2 p-4 rounded-lg bg-gray-200 cursor-default">
                                        <i class="{{ $child['icon'] }} text-2xl"></i>
                                        <span>{{ $child['label'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                            @if (!empty($child['description']))
                                <div
                                    class="absolute hidden group-hover:block
                           bg-gray-500 text-white shadow-xl rounded-lg p-4
                           w-64 z-50 bottom-full
                           text-sm transition-opacity duration-300">
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

