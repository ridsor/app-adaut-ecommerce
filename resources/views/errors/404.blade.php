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

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">

    @vite(['resources/js/app.js'])
</head>

<body>
    <main x-data>

        <div id="layoutError">
            <div id="layoutError_content">
                <div class="container-xl px-4 py-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center mt-4">
                                <img class="img-fluid p-4" src="/assets/img/illustrations/404-error.svg"
                                    alt="" />
                                <p class="lead">URL yang diminta ini tidak ditemukan di server ini.</p>
                                <a class="text-arrow-icon small text-decoration-none d-flex align-items-center justify-content-center"
                                    style="cursor: pointer;"
                                    @click.prevent="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'">
                                    <i class="fa-solid fa-arrow-left me-1"
                                        style="font-size: 14px; transform:translateY(-.5px)"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{-- JS --}}
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
