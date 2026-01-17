<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="{{ auth()->check() && auth()->user()->tema == 0 ? 'dark' : 'light' }}"
      class="{{ auth()->check() && auth()->user()?->tema == 0 ? 'dark' : 'light' }}">

<head>
    @include('layouts.theme.styles')
</head>

<body class="fondo-app font-sans antialiased min-h-screen flex flex-col">

    <!-- Menu Modulos -->
    {{--}}@if (!Request::is('home*'))
        @include('layouts.theme.menu-modulos')
    @endif--}}

    @include('layouts.theme.menu-modulos')

<div class="flex-grow flex flex-col">

    <!-- Page Heading -->
    @if (View::hasSection('header'))
        <header class="menu lg:menu-horizontal w-full">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="flex-grow">
        {{--$slot--}}
        @yield('content')
    </main>

</div>
<!-- Footer -->
@include('layouts.theme.footer')

<!-- Scripts -->
{{--@stack('scripts')--}}
@include('layouts.theme.scripts')

</body>

</html>
