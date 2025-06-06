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

    @vite(['resources/js/app.js', 'resources/css/app.css', 'resources/js/bootstrap.js'])
</head>

<body>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (@json(Session::has('success'))) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{ Session::get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        }
        if (@json(Session::has('info'))) {
            Swal.fire({
                position: "top-end",
                icon: "info",
                title: "{{ Session::get('info') }}",
                showConfirmButton: false,
                timer: 1500
            });
        }
        if (@json(Session::has('error'))) {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "{{ Session::get('error') }}",
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
