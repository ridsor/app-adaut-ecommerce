<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Ryan Syukur" />
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://kit.fontawesome.com/0c8762722d.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    @stack('head')

    @vite(['resources/js/app.js', 'resources/css/app.css', "resources/js/alpine.js"])
</head>

<body>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')

    <div id="preloader"
        class="pe-none position-fixed top-0 bg-light bottom-0 end-0 start-0 z-2 d-flex justify-content-center align-items-center">
        <div style="width: 200px; height: 200px">
            <x-loading />
        </div>
    </div>
    {{-- JS --}}
    <script>
        const preloader = document.getElementById("preloader")
        if (preloader) {
            window.addEventListener('load', () => {
                preloader.remove();
            })
        }
    </script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
