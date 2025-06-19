<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="adaut" />
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="/assets/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="nav-fixed">
    @include('layouts.admin.header')
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('layouts.admin.sidebar')
            <div id="layoutSidenav_content">
                @yield('content')
                @include('layouts.admin.footer')
            </div>
        </div>
        <script src="/assets/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/scripts.js"></script>
        @stack('scripts')
</body>

</html>
