<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Ryan Syukur" />
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="/assets/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    @stack('head')
</head>

<body>
    <main x-data>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title d-flex gap-2">
                                <a class="btn btn-transparent-dark btn-icon" href="{{ route('home') }}"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-left">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg></a>
                                <div class="page-header-icon"><i data-feather="user"></i></div>
                                Pengaturan Akun
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl mt-4">
            <nav class="nav nav-borders">
                <a class="nav-link ms-0 {{ Request::routeIs('account.profile.index') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('account.profile.index') ? route('account.profile.index') : '#' }}">Profil</a>
                <a class="nav-link ms-0 {{ Request::routeIs('account.address.index') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('account.address.index') ? route('account.address.index') : '#' }}">Alamat</a>
                <a class="nav-link ms-0 {{ Request::routeIs('account.security.index') ? 'active' : '' }}"
                    href="{{ !Request::routeIs('account.security.index') ? route('account.security.index') : '#' }}">Keamanan</a>
            </nav>
            <hr class="mt-0 mb-4" />
            <div class="content">
                @yield('content')
            </div>
        </div>
    </main>



    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
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
        if (@json(Session::has('error'))) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{ Session::get('error') }}",
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
