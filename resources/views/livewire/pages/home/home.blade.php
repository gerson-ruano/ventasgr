@extends('layouts.app')
@section('title', 'Home')
@section('content')

    @if(count($modules) > 0)
    <div class="text-center mt-2">
                    <h1 class="text-2xl font-bold mb-2">Bienvenido a tu Panel de Módulos</h1>
                    {{--<p class="text-blue-800 font-semibold">Selecciona un módulo para comenzar.</p>--}}
                </div>
        <div class="flex justify-center items-center p-4 py-0">

            <div class="card w-full max-w-4xl shadow-md">


                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-4">
                    @foreach($modules as $module)
                        <div class="card bg-base-100 shadow-md p-4 hover:shadow-xl ">
                            <div class="card-body text-center">
                                <a href="{{ $module['route'] }}" class="btn btn-xs w-full h-full py-6 text-lg flex justify-center items-center gap-2">
                                    <i class="{{ $module['icon'] }}"></i> {{ $module['label'] }}
                                </a>
                            </div>
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

