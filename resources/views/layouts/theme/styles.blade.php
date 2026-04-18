<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{  setting('app_name')}}</title>
<link rel="shortcut icon" href="{{ asset('img/favicongr.ico') }}">
<link rel="stylesheet" href="{{ asset('fontawesome-free-5.15.4/css/all.min.css')}}">

<!-- Scripts -->
@vite(['resources/js/app.js','resources/css/app.css'])
@livewireStyles

<script>
    // Aplica el tema desde localStorage lo antes posible para evitar parpadeos
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.classList.toggle('dark', theme === 'dark');
</script>

