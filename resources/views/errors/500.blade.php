@extends('layouts.app')
@section('content')
    <div class="container mx-auto mt-2">
        <div class="flex justify-center">
            <div class="w-full md:w-3/4 lg:w-1/2">
                <div class="card bg-white text-black shadow-lg rounded-lg text-center p-5">
                    <div class="card-header text-2xl font-bold text-red-600 mt-3">El recurso no existe</div>
                    <div class="card-body mt-2">
                        <h1 class="text-4xl font-extrabold">Error 500</h1>
                        <p class="text-lg mt-4">
                            <strong>Lo siento, {{-- Auth::user()->name --}}hay un problema de carga.</strong>
                        </p>
                        <p class="mt-2">
                            Si crees que esto es un error, por favor, contacta al administrador del sistema.
                        </p>
                        <a href="{{ url('/') }}" class="btn mt-5 bg-gray-800 hover:bg-gray-700 text-white mt-3">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
