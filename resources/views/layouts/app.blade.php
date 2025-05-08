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

    {{-- CSS --}}
    @stack('styles')
</head>

<body>
    @include('layouts.header')
    @yield('content')

    {{-- JS --}}
    @stack('scripts')
</body>

</html>
