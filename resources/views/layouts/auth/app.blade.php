<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Ryan Syukur" />
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    <link href="/assets/css/styles.css" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            @yield('content')
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="footer-admin mt-auto footer-dark ">
                <div class="container-xl px-4">
                    <div class="row">
                        <div class="small text-white text-center">© 2025 Adaut. All rights reserved.</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="/assets/js/bootsrap.bundle.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
</body>

</html>
