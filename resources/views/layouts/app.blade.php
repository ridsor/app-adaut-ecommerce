<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://kit.fontawesome.com/0c8762722d.js" crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/css/app.css', 'resources/css/bootstrap.css'])

    <!-- CSS -->
    @stack('head')
</head>

<body>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')

    {{-- JS --}}
    <div id="preloader" class="position-fixed top-0 bg-light bottom-0 end-0 start-0 z-2 d-flex justify-content-center align-items-center">
        <div style="width: 200px; height: 200px">
            <x-loading/>
        </div>
    </div>
    <script>
        const preloader = document.getElementById("preloader")
        if(preloader) {
            window.addEventListener('load', () => {
                preloader.remove();
            })
        }
    </script> 
    @stack('scripts')
</body>

</html>
