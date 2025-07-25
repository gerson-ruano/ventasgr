<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{  setting('app_name')}}</title>
<link rel="shortcut icon" href="{{ asset('img/favicongr.ico') }}">
{{--<link rel="icon" type="image/x-icon" href="assets/img/favicongr.ico" />--}}

<!-- Fonts -->
{{--<link rel="preconnect" href="https://fonts.bunny.net">--}}
{{--<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />--}}

{{--<partials type="text/javascript" src="{{ asset('js/pages/dashboard.js') }}"></partials>--}}
<link rel="stylesheet" href="{{ asset('fontawesome-free-5.15.4/css/all.min.css')}}">

<!-- Scripts -->
@vite(['resources/js/app.js','resources/css/app.css'])
{{--@vite(['resources/js/pages/dashboard.js', 'resources/js/app.js','resources/css/app.css'])--}}
@livewireStyles

{{--<partials src="//unpkg.com/alpinejs" defer></partials>--}}
<script>
    // Aplica el tema desde localStorage lo antes posible para evitar parpadeos
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.classList.toggle('dark', theme === 'dark');
</script>

