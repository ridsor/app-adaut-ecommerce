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

    <!-- Vendor CSS Files -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://kit.fontawesome.com/0c8762722d.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link href="/assets/css/aos.css" rel="stylesheet" />
    <link href="/assets/css/invent.css" rel="stylesheet" />
    <link href="/assets/css/invent-bootstrap.css" rel="stylesheet" />
    @stack('head')
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- =======================================================
  * Template Name: Invent
  * Template URL: https://bootstrapmade.com/invent-bootstrap-business-template/
  * Updated: May 12 2025 with Bootstrap v5.3.6
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page adaut">
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')

    {{-- JS --}}
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="fa-solid fa-arrow-up"></i></a>
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
    <script src="/assets/js/aos.js"></script>
    <script src="/assets/js/invent.js"></script>
    @stack('scripts')
</body>

</html>
